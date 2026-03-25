<?php

namespace App\Tests;
use PHPUnit\Framework\TestCase;

use App\Models\CompanyModel;
use App\Models\SqlDatabase;

class CompanyModelTest extends TestCase {

    public function testGetCompanyById(){

        $connection = $this->createStub(SqlDatabase::class);
        
        $connection->method('getRecord')->willReturn([
            'ID_entreprise' => 2, 
            'Nom_entreprise' => 'Microsoft', 
            'Description' => 'Leader du logiciel', 
            'Contact' => 'contact@microsoft.com'
            ]);
        
        $model = new CompanyModel($connection);

        $company = $model->getCompanyById(2);

        $this->assertEquals('Microsoft', $company['Nom_entreprise']);

        
    }

    public function testCreateCompany() {

        $connection = $this->createMock(SqlDatabase::class);

        // Simulation du contenu de la base de données
        $connection->method('insertRecord')->willReturn(true);

        $connection->expects($this->once())
                   ->method('insertRecord')
                   ->with([
                    'Nom_entreprise' => 'Test Company',
                    'Description' => 'This is a test company',
                    'Contact' => 'contact@testcompany.com'
                   ]);
        
        $model = new CompanyModel($connection);

        $result = $model->createCompany([
            'Nom_entreprise' => 'Test Company',
            'Description' => 'This is a test company',
            'Contact' => 'contact@testcompany.com'
        ]);

        $this->assertTrue($result);
    }

    public function testUpdateCompany() {

        $connection = $this->createStub(SqlDatabase::class);
        
        $connection->method('getRecord')->willReturn([
            ['ID_entreprise' => 1, 'Nom_entreprise' => 'Google', 'Description' => 'Géant de la technologie', 'Contact' => 'contact@google.com'],
            ['ID_entreprise' => 2, 'Nom_entreprise' => 'Microsoft', 'Description' => 'Leader du logiciel', 'Contact' => 'contact@microsoft.com'],
            ['ID_entreprise' => 3, 'Nom_entreprise' => 'Apple', 'Description' => 'Pionnier de l\'innovation technologique', 'Contact' => 'contact@apple.com']
            ]);
        
        $model = new CompanyModel($connection);

        $company = $model->updateCompany(2, ['Nom_entreprise' => 'Updated Microsoft']);

        $this->assertEquals('Updated Microsoft', $company['Nom_entreprise']);
    }

    public function testDeleteCompany() {

        $connection = $this->createMock(SqlDatabase::class);

        $connection->method('deleteRecord')->willReturn(true);

        $connection->expects($this->once())
                   ->method('deleteRecord')
                   ->with(2);
        
        $model = new CompanyModel($connection);

        $result = $model->deleteCompany(2);

        $this->assertTrue($result);
    }

    public function testSearchCompanies() {

        $connection = $this->createStub(SqlDatabase::class);
        
        $connection->method('getAllRecords')->willReturn([
            ['ID_entreprise' => 1, 'Nom_entreprise' => 'Google', 'Description' => 'Géant de la technologie', 'Contact' => 'contact@google.com'],
            ['ID_entreprise' => 2, 'Nom_entreprise' => 'Microsoft', 'Description' => 'Leader du logiciel', 'Contact' => 'contact@microsoft.com'],
            ['ID_entreprise' => 3, 'Nom_entreprise' => 'Apple', 'Description' => 'Pionnier de l\'innovation technologique', 'Contact' => 'contact@apple.com']
            ]);
        
        $model = new CompanyModel($connection);

        $companies = $model->searchCompanies('Microsoft');

        $this->assertCount(1, $companies);
        $this->assertEquals('Microsoft', $companies[0]['Nom_entreprise']);
    }

    public function testRateCompany() {

        $connection = $this->createMock(SqlDatabase::class);

        $connection->method('insertRecord')->willReturn(true);

        $connection->expects($this->once())
                   ->method('insertRecord')
                   ->with([
                    'ID_entreprise' => 2,
                    'ID_utilisateur' => 1,
                    'Note_entreprise' => 4
                   ]);
        
        $model = new CompanyModel($connection);

        $result = $model->rateCompany(2, 1, 4);

        $this->assertTrue($result);
    }
}