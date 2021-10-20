<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\CompanyBusinessUnitDataImport\Communication\Plugin;

use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Spryker\Zed\CompanyBusinessUnitDataImport\Communication\Plugin\CompanyBusinessUnitDataImportPlugin;
use Spryker\Zed\DataImport\Business\Exception\DataImportException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CompanyBusinessUnitDataImport
 * @group Communication
 * @group Plugin
 * @group CompanyBusinessUnitDataImportPluginTest
 * Add your own group annotations below this line
 */
class CompanyBusinessUnitDataImportPluginTest extends AbstractCompanyBusinessUnitDataImportUnitTest
{
    /**
     * @var string
     */
    protected const COMPANY_KEY = 'spryker';

    /**
     * @var string
     */
    protected const COMPANY_BUSINESS_UNIT_KEY = 'spryker-business-unit';

    /**
     * @var string
     */
    protected const COMPANY_CHILD_BUSINESS_UNIT_KEY = 'child-spryker-business-unit';

    /**
     * @var string
     */
    protected const IMPORT_COMPANY_BUSINESS_UNIT_CSV = 'import/company_business_unit.csv';

    /**
     * @var string
     */
    protected const IMPORT_COMPANY_BUSINESS_UNIT_WITH_INVALID_COMPANY_CSV = 'import/company_business_unit_with_invalid_company.csv';

    /**
     * @var string
     */
    protected const IMPORT_COMPANY_BUSINESS_UNIT_WITH_INVALID_PARENT_CSV = 'import/company_business_unit_with_invalid_parent.csv';

    /**
     * @var \SprykerTest\Zed\CompanyBusinessUnitDataImport\CompanyBusinessUnitDataImportCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testImportImportsCompanyBusinessUnit(): void
    {
        $this->tester->truncateCompanyBusinessUnitRelations();

        $this->tester->haveCompany([CompanyTransfer::KEY => static::COMPANY_KEY]);

        $dataImportConfigurationTransfer = $this->getDataImportConfigurationTransfer(
            static::IMPORT_COMPANY_BUSINESS_UNIT_CSV,
        );

        $companyBusinessUnitDataImportPlugin = new CompanyBusinessUnitDataImportPlugin();
        $dataImporterReportTransfer = $companyBusinessUnitDataImportPlugin->import($dataImportConfigurationTransfer);

        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);

        $this->tester->assertDatabaseTableContainsData();
    }

    /**
     * @return void
     */
    public function testImportThrowsExceptionWhenCompanyNotFound(): void
    {
        $this->expectException(DataImportException::class);
        $this->expectExceptionMessage('Could not find company by key "invalid company"');
        $this->tester->truncateCompanyBusinessUnitRelations();

        $dataImportConfigurationTransfer = $this->getDataImportConfigurationTransfer(
            static::IMPORT_COMPANY_BUSINESS_UNIT_WITH_INVALID_COMPANY_CSV,
        );

        $companyBusinessUnitDataImportPlugin = new CompanyBusinessUnitDataImportPlugin();

        $companyBusinessUnitDataImportPlugin->import($dataImportConfigurationTransfer);
    }

    /**
     * @return void
     */
    public function testImportThrowsExceptionWhenParentBusinessUnitNotFound(): void
    {
        $this->expectException(DataImportException::class);
        $this->expectExceptionMessage('Could not find company business unit by key "invalid parent"');
        $this->tester->truncateCompanyBusinessUnitRelations();

        $companyTransfer = $this->tester->haveActiveCompany([CompanyTransfer::KEY => static::COMPANY_KEY]);
        $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
            CompanyBusinessUnitTransfer::KEY => static::COMPANY_BUSINESS_UNIT_KEY,
        ]);
        $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
            CompanyBusinessUnitTransfer::KEY => static::COMPANY_CHILD_BUSINESS_UNIT_KEY,
        ]);
        $dataImportConfigurationTransfer = $this->getDataImportConfigurationTransfer(
            static::IMPORT_COMPANY_BUSINESS_UNIT_WITH_INVALID_PARENT_CSV,
        );

        $companyBusinessUnitDataImportPlugin = new CompanyBusinessUnitDataImportPlugin();
        $companyBusinessUnitDataImportPlugin->import($dataImportConfigurationTransfer);
    }
}
