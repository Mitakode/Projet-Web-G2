<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Controllers\CompanyController;
use App\Models\CompanyModel;
use Twig\Environment as Twig_Environment;

/**
 * Unit tests for CompanyController.
 * Tests verify correct rendering, validation, and model interaction.
 * Uses mocks to isolate controller logic from external dependencies (Twig, Model, DB).
 */
class CompanyControllerTest extends TestCase
{
    /**
     * Reset superglobals after each test to prevent state pollution.
     */
    protected function tearDown(): void
    {
        parent::tearDown();
        $_GET = [];
        $_POST = [];
        $_SERVER = [];
        $_SESSION = [];
    }

    /**
     * Test: List action renders companies with search filter and pagination.
     * Scenario: Student searches companies, expects paginated results.
     */
    public function testListRendersCompaniesWithSearchAndPagination()
    {
        // Setup: Simulate GET request with search term and pagination
        $_GET['recherche'] = 'dev';
        $_GET['page'] = 1;
        $_SESSION['user_role'] = 'etudiant';
        $_SESSION['user_id'] = 42;

        // Mock data: one company returned by search
        $companyData = [
            ['ID_entreprise' => 1, 'Nom_entreprise' => 'TestCo', 'Description' => 'A company', 'Contact' => 'c@test.co']
        ];

        // Mock model: searchCompanies('dev', 42) returns company data
        $modelMock = $this->createMock(CompanyModel::class);
        $modelMock->method('searchCompanies')
                  ->with('dev', 42)
                  ->willReturn($companyData);

        // Create Twig mock with getMockBuilder (no constructor needed)
        $twigMock = $this->getMockBuilder(Twig_Environment::class)
                         ->disableOriginalConstructor() // Skip Twig_Environment constructor
                         ->getMock(); // Build the mock object
        
        // Define expectations: render() must be called exactly once
        $twigMock->expects($this->once())
                 ->method('render')
                 ->with('Companies.html.twig', // First arg: template name
                        $this->callback(function ($context) use ($companyData) { // Second arg: context array
                            // Validate context contains expected keys
                            return isset($context['entreprises_page'])
                                && $context['entreprises_page'] === $companyData // Check companies match
                                && $context['total_pages'] === 1 // Check pagination: 1 page total
                                && $context['current_page'] === 1 // Check current page
                                && $context['search_term'] === 'dev'; // Check search term
                        }))
                 ->willReturn('COMPANIES_RENDERED'); // Mock return value

        // Execute: Create controller and call list()
        $controller = new CompanyController($twigMock, $modelMock);

        // Capture output (controller echoes rendered template)
        ob_start();
        $controller->list();
        $output = ob_get_clean();

        // Assert: Output matches Twig render result
        $this->assertSame('COMPANIES_RENDERED', $output);
    }

    /**
     * Test: Create action displays empty form on GET request.
     * Scenario: Admin accesses form, no POST data submitted.
     */
    public function testCreateDisplaysFormOnGetRequest()
    {
        // Setup: GET request, admin session
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SESSION['user_role'] = 'admin';
        $_SESSION['user_id'] = 99;

        $modelMock = $this->createMock(CompanyModel::class);

        $twigMock = $this->getMockBuilder(Twig_Environment::class)
                         ->disableOriginalConstructor()
                         ->getMock();
        $twigMock->expects($this->once())
                 ->method('render')
                 ->with('CompaniesForm.html.twig', [
                     'is_edit' => false,
                     'entreprise' => [],
                     'error' => ''
                 ])
                 ->willReturn('FORM_RENDERED');

        // Execute: Call create() action
        $controller = new CompanyController($twigMock, $modelMock);

        ob_start();
        $controller->create();
        $output = ob_get_clean();

        // Assert: Form rendered successfully
        $this->assertSame('FORM_RENDERED', $output);
    }

    /**
     * Test: Create action validates POST data and shows errors on invalid input.
     * Scenario: Admin submits empty form fields, validation fails.
     */
    public function testCreateShowsErrorsWhenPostDataIsInvalid()
    {
        // Setup: POST request with empty fields
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['nameCompany'] = '';
        $_POST['descriptionCompany'] = '';
        $_POST['contactCompany'] = '';
        $_SESSION['user_role'] = 'admin';
        $_SESSION['user_id'] = 99;

        $modelMock = $this->createMock(CompanyModel::class);
        $modelMock->expects($this->never())->method('createCompany');

        $twigMock = $this->getMockBuilder(Twig_Environment::class)
                         ->disableOriginalConstructor()
                         ->getMock();

        $twigMock->expects($this->once())
                 ->method('render')
                 ->with('CompaniesForm.html.twig', $this->callback(function ($context) {
                     return $context['is_edit'] === false
                         && $context['entreprise'] === ['Nom_entreprise' => '', 'Description' => '', 'Contact' => '']
                         && $context['error'] === 'nameCompany&contactCompany&descriptionCompany&';
                 }))
                 ->willReturn('FORM_ERROR_RENDERED');

        // Execute: Call create() action with invalid data
        $controller = new CompanyController($twigMock, $modelMock);

        ob_start();
        $controller->create();
        $output = ob_get_clean();

        // Assert: Form re-rendered with error messages
        $this->assertSame('FORM_ERROR_RENDERED', $output);
    }

    /**
     * Test: Update action renders form pre-populated with existing company data.
     * Scenario: Admin requests edit form for company ID 1.
     */
    public function testUpdateRendersFormForExistingCompany()
    {
        // Setup: GET request with company ID
        $_GET['id'] = 1;
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SESSION['user_role'] = 'admin';
        $_SESSION['user_id'] = 99;

        // Mock data: existing company
        $company = ['ID_entreprise' => 1, 'Nom_entreprise' => 'ACME', 'Description' => 'Desc', 'Contact' => 'contact@acme.com'];

        // Mock model: getCompanyById(1) returns company data
        $modelMock = $this->createMock(CompanyModel::class);
        $modelMock->method('getCompanyById')->with(1)->willReturn($company);

        // Mock Twig: expect render with company data and edit flag
        $twigMock = $this->getMockBuilder(Twig_Environment::class)
                         ->disableOriginalConstructor()
                         ->getMock();

        $twigMock->expects($this->once())
                 ->method('render')
                 ->with('CompaniesForm.html.twig', [
                     'entreprise' => $company,
                     'is_edit' => true
                 ])
                 ->willReturn('UPDATE_FORM_RENDERED');

        // Execute: Call update() action
        $controller = new CompanyController($twigMock, $modelMock);

        ob_start();
        $controller->update();
        $output = ob_get_clean();

        // Assert: Edit form rendered with company data
        $this->assertSame('UPDATE_FORM_RENDERED', $output);
    }
}
