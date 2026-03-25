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

        $connection = $this->createMock(SqlDatabase::class);
        $connection->method('updateRecord')->willReturn(true);
        
        $model = new CompanyModel($connection);
        
        $result = $model->updateCompany(2, ['Nom_entreprise' => 'Updated Microsoft']);

        $this->assertTrue($result);
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

        $connection = $this->createMock(SqlDatabase::class);
        $pdoMock = $this->createMock(\PDO::class);
        $stmtMock = $this->createMock(\PDOStatement::class);
        
        $fakeResults = [
            ['ID_entreprise' => 2, 'Nom_entreprise' => 'Microsoft', 'Description' => 'Leader du logiciel', 'Moyenne_Note' => 4.5]
        ];

        $connection->method('getConnection')->willReturn($pdoMock);
        $pdoMock->method('prepare')->willReturn($stmtMock);
        $stmtMock->method('fetchAll')->willReturn($fakeResults);

        $model = new CompanyModel($connection);

        $companies = $model->searchCompanies('Microsoft');

        $this->assertCount(1, $companies);
        $this->assertEquals('Microsoft', $companies[0]['Nom_entreprise']);
    }

    public function testRateCompany() {

        $connection = $this->createMock(SqlDatabase::class);
        $pdoMock = $this->createMock(\PDO::class);
        $stmtMock = $this->createMock(\PDOStatement::class);

        $connection->method('getConnection')->willReturn($pdoMock);
    
        // On simule toute la chaîne PDO (prepare -> execute -> fetch)
        $pdoMock->method('prepare')->willReturn($stmtMock);
        $stmtMock->method('execute')->willReturn(true);
        $stmtMock->method('fetch')->willReturn(['count' => 0]);
        
        $model = new CompanyModel($connection);

        $result = $model->rateCompany(2, 1, 4);

        $this->assertTrue($result);
    }
}