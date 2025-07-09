<?php
declare(strict_types=1);

namespace app\bootstrap;

use lib\ApiIntegrator\BaseHttpClient;
use lib\Serializer\EnumDenormalizer;
use src\Integration\FinanceReport\FinanceReportFetcherInterface;
use src\Integration\FinanceReport\Legat\LegatFinanceReportFetcher;
use src\Integration\FinanceReport\Mock\MockFinanceReportFetcher;
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
                3
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
                ])
            );
        });

        $container->setSingleton(FinanceReportFetcherInterface::class, function () use ($container) {
            return ($_ENV['FINANCE_REPORT_TEST_MODE'] ?? true)
                ? $container->get(MockFinanceReportFetcher::class)
                : $container->get(LegatFinanceReportFetcher::class);
        });
    }
}