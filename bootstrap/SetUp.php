<?php
declare(strict_types=1);

namespace app\bootstrap;

use lib\ApiIntegrator\BaseHttpClient;
use lib\EnvGetter;
use lib\Serializer\EnumDenormalizer;
use Psr\Log\LoggerInterface;
use src\Integration\Legat\Api\LegatAvailableFinancialReportsFetcher;
use src\Integration\Legat\Api\LegatCommonIssuerInfoFetcher;
use src\Integration\Legat\Api\LegatEgrEventFetcher;
use src\Integration\Legat\Api\LegatEmployeeAmountFetcher;
use src\Integration\Legat\CommonIssuerInfoFetcherInterface;
use src\Integration\Legat\EmployeeAmountFetcherInterface;
use src\Integration\Legat\LegatAvailableFinancialReportsFetcherInterface;
use src\Integration\Legat\LegatEgrEventsFetcherInterface;
use src\Integration\Legat\FinanceReportFetcherInterface;
use src\Integration\Legat\Api\LegatFinanceReportFetcher;
use src\Integration\Legat\Mock\MockLegatFetcherLegat;
use Symfony\Component\HttpClient\CurlHttpClient;
use Symfony\Component\HttpClient\RetryableHttpClient;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\Extractor\SerializerExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\Serializer\Encoder\ChainEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AttributeLoader;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Yii;
use yii\base\BootstrapInterface;

class SetUp implements BootstrapInterface
{
    /**
     * @inheritDoc
     */
    public function bootstrap($app): void
    {
        Yii::setAlias('@views', dirname(__DIR__) . '/views');

        $container = \Yii::$container;

        $container->setSingleton(HttpClientInterface::class, function () use ($container) {
            return new RetryableHttpClient(
                $container->get(CurlHttpClient::class),
                null,
                EnvGetter::getInt('API_RETRIES')
            );
        });
        $container->setSingleton(ValidatorInterface::class, function () use ($container) {
            return Validation::createValidatorBuilder()
                ->getValidator();
        });
        $container->setSingleton(SerializerInterface::class, function () {
            $classMetadataFactory = new ClassMetadataFactory(new AttributeLoader());

            // Настраиваем экстракторы
            $reflectionExtractor = new ReflectionExtractor();
            $phpDocExtractor = new PhpDocExtractor();
            $serializerExtractor = new SerializerExtractor($classMetadataFactory);

            // Создаем PropertyInfoExtractor со всеми доступными экстракторами
            $propertyInfoExtractor = new PropertyInfoExtractor(
                // List extractors
                [$reflectionExtractor],
                // Type extractors
                [$phpDocExtractor, $reflectionExtractor],
                // Description extractors
                [$phpDocExtractor],
                // Access extractors
                [$reflectionExtractor],
                // Modifiable extractors
                [$reflectionExtractor]
            );

            return new Serializer(
                [
                    new EnumDenormalizer(),
                    new ObjectNormalizer(
                        classMetadataFactory: $classMetadataFactory,
                        propertyAccessor: new PropertyAccessor(),
                        propertyTypeExtractor: $propertyInfoExtractor
                    ),
                    new ArrayDenormalizer(),
                ],
                [
                    new JsonEncoder(),
                    new ChainEncoder(),
                ]
            );

        });
        $container->setSingleton(BaseHttpClient::class, function () use ($container) {
            return new BaseHttpClient(
                $container->get(HttpClientInterface::class)->withOptions([
                    'verify_peer' => false,
                    'verify_host' => false,
                ]),
            );
        });

        $container->setSingleton(FinanceReportFetcherInterface::class, function () use ($container) {
            return EnvGetter::getBool('LEGAT_TEST_MODE', true)
                ? $container->get(MockLegatFetcherLegat::class)
                : $container->get(LegatFinanceReportFetcher::class);
        });

        $container->setSingleton(CommonIssuerInfoFetcherInterface::class, function () use ($container) {
            return EnvGetter::getBool('LEGAT_TEST_MODE', true)
                ? $container->get(MockLegatFetcherLegat::class)
                : $container->get(LegatCommonIssuerInfoFetcher::class);
        });

        $container->setSingleton(LegatEgrEventsFetcherInterface::class, function () use ($container) {
            return EnvGetter::getBool('LEGAT_TEST_MODE', true)
                ? $container->get(MockLegatFetcherLegat::class)
                : $container->get(LegatEgrEventFetcher::class);
        });

        $container->setSingleton(EmployeeAmountFetcherInterface::class, function () use ($container) {
            return EnvGetter::getBool('LEGAT_TEST_MODE', true)
                ? $container->get(MockLegatFetcherLegat::class)
                : $container->get(LegatEmployeeAmountFetcher::class);
        });

        $container->setSingleton(LegatAvailableFinancialReportsFetcherInterface::class, function () use ($container) {
            return EnvGetter::getBool('LEGAT_TEST_MODE', true)
                ? $container->get(MockLegatFetcherLegat::class)
                : $container->get(LegatAvailableFinancialReportsFetcher::class);
        });
    }
}