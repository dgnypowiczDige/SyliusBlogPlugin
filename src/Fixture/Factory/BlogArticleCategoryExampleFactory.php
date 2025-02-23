<?php

declare(strict_types=1);

namespace Odiseo\SyliusBlogPlugin\Fixture\Factory;

use Faker\Factory;
use Faker\Generator as FakerGenerator;
use Generator;
use Odiseo\BlogBundle\Model\ArticleCategoryInterface;
use Sylius\Bundle\CoreBundle\Fixture\Factory\AbstractExampleFactory;
use Sylius\Component\Core\Formatter\StringInflector;
use Sylius\Component\Locale\Model\LocaleInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class BlogArticleCategoryExampleFactory extends AbstractExampleFactory
{
    private FactoryInterface $articleCategoryFactory;
    private RepositoryInterface $localeRepository;
    private FakerGenerator $faker;
    private OptionsResolver $optionsResolver;

    public function __construct(
        FactoryInterface $articleCategoryFactory,
        RepositoryInterface $localeRepository
    ) {
        $this->articleCategoryFactory = $articleCategoryFactory;
        $this->localeRepository = $localeRepository;

        $this->faker = Factory::create();
        $this->optionsResolver = new OptionsResolver();

        $this->configureOptions($this->optionsResolver);
    }

    public function create(array $options = []): ArticleCategoryInterface
    {
        $options = $this->optionsResolver->resolve($options);

        /** @var ArticleCategoryInterface $articleCategory */
        $articleCategory = $this->articleCategoryFactory->createNew();
        $articleCategory->setCode($options['code']);
        $articleCategory->setEnabled($options['enabled']);

        foreach ($this->getLocales() as $localeCode) {
            $articleCategory->setCurrentLocale((string) $localeCode);
            $articleCategory->setFallbackLocale((string) $localeCode);

            $articleCategory->setTitle($options['title']);
            $articleCategory->setSlug($options['slug']);
        }

        return $articleCategory;
    }

    private function getLocales(): Generator
    {
        /** @var LocaleInterface[] $locales */
        $locales = $this->localeRepository->findAll();
        foreach ($locales as $locale) {
            yield $locale->getCode();
        }
    }

    protected function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefault('code', function (Options $options): string {
                return StringInflector::nameToCode((string) $options['title']);
            })
            ->setDefault('enabled', function (Options $_options): bool {
                return $this->faker->boolean(90);
            })
            ->setAllowedTypes('enabled', 'bool')
            ->setDefault('title', function (Options $_options): string {
                return $this->faker->text(20);
            })
            ->setDefault('slug', function (Options $options): string {
                return StringInflector::nameToCode((string) $options['title']);
            })
        ;
    }
}
