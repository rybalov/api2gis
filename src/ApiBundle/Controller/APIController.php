<?php

namespace ApiBundle\Controller;

use ApiBundle\Entity\CategoryRepository;
use ApiBundle\Entity\CompanyRepository;
use ApiBundle\Entity\FeatureRepository;
use ApiBundle\Traits\APIResponsible;
use ApiBundle\Traits\APIScrollable;
use ApiBundle\Entity\Company;
use ApiBundle\Entity\FeaturePoint;
use ApiBundle\Exception\APIIncorrectRequestException;
use ApiBundle\Exception\APINothingFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Контроллер для обработки API-запросов.
 *
 * @author Nikita Rybalov <nikita.rybalov@gmail.com>
 */
class APIController extends Controller implements APIControllerInterface
{
    use APIScrollable;
    use APIResponsible;

    /**
     * Поиск зданий.
     *
     * @param Request $request
     * @Route("/api/search/buildings", name="api_search_buildings")
     *
     * @return Response
     *
     * @throws APIIncorrectRequestException|APINothingFoundException
     */
    public function searchBuildingsAction(Request $request)
    {
        if (true !== $this->isValidRequest($request)) {
            throw new APIIncorrectRequestException();
        }

        /** @var FeatureRepository $repository */
        $repository = $this->get('doctrine')->getRepository('ApiBundle:FeaturePoint');
        $scroll     = $this->getScroll($request);

        switch ($request->get('type')) {
            case self::SEARCH_BUILD_TYPE_STREET:
                $result = $repository->findByStreet($request->get('street'), $scroll->limit, $scroll->offset);
                break;
            case self::SEARCH_BUILD_TYPE_ALL:
                $result = $repository->findAllEx($scroll->limit, $scroll->offset);
                break;
            default:
                throw new APIIncorrectRequestException();
        }

        if (!count($result)) {
            throw new APINothingFoundException();
        }

        return $this->prepareResponse($result, $this->get('jms_serializer'), $serializationContext = 'buildings');
    }

    /**
     * Поиск категорий.
     *
     * @param Request $request
     * @Route("/api/search/categories", name="api_search_categories")
     *
     * @return Response
     *
     * @throws APIIncorrectRequestException|APINothingFoundException
     */
    public function searchCategoriesAction(Request $request)
    {
        if (true !== $this->isValidRequest($request)) {
            throw new APIIncorrectRequestException();
        }

        /** @var CategoryRepository $repository */
        $repository = $this->get('doctrine')->getRepository('ApiBundle:Category');
        $scroll     = $this->getScroll($request);

        switch ($request->get('type')) {
            case self::SEARCH_CATEGORY_TYPE_NAME:
                $result = $repository->findByName($request->get('name'), $scroll->limit, $scroll->offset);
                break;
            case self::SEARCH_CATEGORY_TYPE_ALL:
                $result = $repository->findBy($criteria = [], $orderBy = ['lft' => 'ASC'], $scroll->limit, $scroll->offset);
                break;
            default:
                throw new APIIncorrectRequestException();
        }

        if (!count($result)) {
            throw new APINothingFoundException();
        }

        return $this->prepareResponse($result, $this->get('jms_serializer'), $serializationContext = 'categories');
    }

    /**
     * Поиск компаний.
     *
     * @param Request $request
     * @Route("/api/search/companies", name="api_search_companies")
     *
     * @return Response
     *
     * @throws APIIncorrectRequestException|APINothingFoundException
     */
    public function searchCompaniesAction(Request $request)
    {
        if (true !== $this->isValidRequest($request)) {
            throw new APIIncorrectRequestException();
        }

        /** @var CompanyRepository $repository */
        $repository = $this->get('doctrine')->getRepository('ApiBundle:Company');
        $scroll     = $this->getScroll($request);

        $serializationContext = 'companies';

        switch ($request->get('type')) {
            case self::SEARCH_COMP_TYPE_ID:
                $result = $repository->find($request->get('id'));
                break;
            case self::SEARCH_COMP_TYPE_ADDRESS:
                $result = $repository->findByAddress($request->get('city'), $request->get('street'), $request->get('house'), $scroll->limit, $scroll->offset);
                break;
            case self::SEARCH_COMP_TYPE_NAME:
                $result = $repository->findByName($request->get('name'), $scroll->limit, $scroll->offset);
                break;
            case self::SEARCH_COMP_TYPE_CATEGORY:
                $category = $this->get('doctrine')->getRepository('ApiBundle:Category')->find($request->get('category'));
                if (!$category) {
                    throw new APIIncorrectRequestException();
                }
                $result = $repository->findByCategory($category, $request->get('nested', $findNested = 0), $scroll->limit, $scroll->offset);
                break;
            case self::SEARCH_COMP_TYPE_RADIUS:
                $result = $repository->findNearby($request->get('lat'), $request->get('lon'), $request->get('radius'));
                $serializationContext = 'companies_bound_radius';
                break;
            case self::SEARCH_COMP_TYPE_BOUND:
                $b = $request->get('bound');
                $result = $repository->findWithinBound($b['lat1'], $b['lon1'], $b['lat2'], $b['lon2']);
                $serializationContext = 'companies_bound_radius';
                break;
            case self::SEARCH_COMP_TYPE_ALL:
                $result = $repository->findAllEx($scroll->limit, $scroll->offset);
                break;
            default:
                throw new APIIncorrectRequestException();
        }

        if (!count($result)) {
            throw new APINothingFoundException();
        }

        return $this->prepareResponse($result, $this->get('jms_serializer'), $serializationContext);
    }

    /**
     * Валидация входных параметров.
     *
     * @param Request $r
     *
     * @return bool
     */
    private function isValidRequest(Request $r)
    {
        switch ($r->get('type')) {
            case self::SEARCH_COMP_TYPE_ID:
                return true;
            case self::SEARCH_COMP_TYPE_ADDRESS:
                return ($r->get('city')
                    && $r->get('street')
                    && $r->get('house')
                );
            case self::SEARCH_COMP_TYPE_NAME:
                return true;
            case self::SEARCH_COMP_TYPE_CATEGORY:
                return (!empty($r->get('category'))
                    && (empty($r->get('nested'))
                        || $r->get('nested') == 1
                    )
                );
            case self::SEARCH_COMP_TYPE_RADIUS:
                return ($r->get('lat')
                    && $r->get('lon')
                    && $r->get('radius')
                );
            case self::SEARCH_COMP_TYPE_BOUND:
                return (($b = $r->get('bound'))
                    && !empty($b['lat1'])
                    && !empty($b['lon1'])
                    && !empty($b['lat2'])
                    && !empty($b['lon2'])
                );
            case self::SEARCH_COMP_TYPE_ALL:
                return true;
            case self::SEARCH_BUILD_TYPE_STREET:
                return true;
            case self::SEARCH_BUILD_TYPE_ALL:
                return true;
        }
    }
}
