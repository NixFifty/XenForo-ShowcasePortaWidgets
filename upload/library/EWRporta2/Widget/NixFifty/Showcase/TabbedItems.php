<?php

class EWRporta2_Widget_NixFifty_Showcase_TabbedItems extends XenForo_Model
{
    public function getCachedData($widget, $options)
    {
        /** @var NFLJ_Showcase_Model_Item $scItemModel */
        $scItemModel = $this->getModelFromCache('NFLJ_Showcase_Model_Item');
        /** @var NFLJ_Showcase_Model_Category $scCategoryModel */
        $scCategoryModel = $this->getModelFromCache('NFLJ_Showcase_Model_Category');
        $visitor = XenForo_Visitor::getInstance();

        $featuredItems = array();
        $recentItems = array();
        $popularItems = array();
        $topRatedItems = array();
        $mostReviewedItems = array();
        $mostLikedItems = array();

        if ($visitor['permissions']['nfljsc']['viewShowcase'])
        {
            $categoryIds = empty($options['nixfifty_showcase_tabbeditems_category']) ? array(): $options['nixfifty_showcase_tabbeditems_category'];

            // These are the cat id's that the viewing user is allowed to "view"
            $viewableCategories = $scCategoryModel->getViewableCategories();
            $viewableCategories = array_keys($viewableCategories);

            // These are the category ID's that are contained in BOTH arrays (to ensure the user only see's content they have permission to view)
            $catIds = array_intersect($categoryIds, $viewableCategories);

            $conditions = array(
                'category_id' => $catIds
            );

            $includeAttachments = $options['nixfifty_showcase_tabbeditems_attachments'] ? true : false;

            if ($options['nixfifty_showcase_tabbeditems_featured'])
            {
                $conditions['featured'] = true;

                $featuredItems = $scItemModel->getItems($conditions, array(
                    'join' => NFLJ_Showcase_Model_Item::FETCH_CATEGORY
                        | NFLJ_Showcase_Model_Item::FETCH_USER,
                    'limit' => $options['nixfifty_showcase_tabbeditems_limit'],
                    'order' => 'featured',
                    'direction' => 'DESC'
                ));
                $featuredItems = $scItemModel->prepareItems($featuredItems, $includeAttachments);

                unset ($conditions['featured']);
            }

            if ($options['nixfifty_showcase_tabbeditems_recent'])
            {
                $recentItems = $scItemModel->getItems($conditions, array(
                    'join' => NFLJ_Showcase_Model_Item::FETCH_CATEGORY
                        | NFLJ_Showcase_Model_Item::FETCH_USER,
                    'limit' => $options['nixfifty_showcase_tabbeditems_limit'],
                    'order' => 'recent',
                    'direction' => 'DESC'
                ));
                $recentItems = $scItemModel->prepareItems($recentItems, $includeAttachments);
            }

            if ($options['nixfifty_showcase_tabbeditems_popular'])
            {
                $popularItems = $scItemModel->getItems($conditions, array(
                    'join' => NFLJ_Showcase_Model_Item::FETCH_CATEGORY
                        | NFLJ_Showcase_Model_Item::FETCH_USER,
                    'limit' => $options['nixfifty_showcase_tabbeditems_limit'],
                    'order' => 'popular',
                    'direction' => 'DESC'
                ));
                $popularItems = $scItemModel->prepareItems($popularItems, $includeAttachments);
            }

            if ($options['nixfifty_showcase_tabbeditems_top'])
            {
                $conditions['rated'] = true;

                $topRatedItems = $scItemModel->getItems($conditions, array(
                    'join' => NFLJ_Showcase_Model_Item::FETCH_CATEGORY
                        | NFLJ_Showcase_Model_Item::FETCH_USER,
                    'limit' => $options['nixfifty_showcase_tabbeditems_limit'],
                    'order' => 'rated',
                    'direction' => 'DESC'
                ));
                $topRatedItems = $scItemModel->prepareItems($topRatedItems, $includeAttachments);

                unset ($conditions['rated']);
            }

            if (isset($options['nixfifty_showcase_tabbeditems_reviews']) && $options['nixfifty_showcase_tabbeditems_reviews'])
            {
                $conditions['reviewed'] = true;

                $mostReviewedItems = $scItemModel->getItems($conditions, array(
                    'join' => NFLJ_Showcase_Model_Item::FETCH_CATEGORY
                        | NFLJ_Showcase_Model_Item::FETCH_USER,
                    'limit' => $options['nixfifty_showcase_tabbeditems_limit'],
                    'order' => 'reviewed',
                    'direction' => 'DESC'
                ));
                $mostReviewedItems = $scItemModel->prepareItems($mostReviewedItems, $includeAttachments);

                unset ($conditions['reviewed']);
            }

            if (isset($options['nixfifty_showcase_tabbeditems_liked']) && $options['nixfifty_showcase_tabbeditems_liked'])
            {
                $mostLikedItems = $scItemModel->getItems($conditions, array(
                    'join' => NFLJ_Showcase_Model_Item::FETCH_CATEGORY
                        | NFLJ_Showcase_Model_Item::FETCH_USER,
                    'limit' => $options['nixfifty_showcase_tabbeditems_limit'],
                    'order' => 'liked',
                    'direction' => 'DESC'
                ));
                $mostLikedItems = $scItemModel->prepareItems($mostLikedItems, $includeAttachments);
            }

            return array(
                'displayImages' => $includeAttachments,
                'featuredItems' => $featuredItems,
                'recentItems' => $recentItems,
                'popularItems' => $popularItems,
                'topRatedItems' => $topRatedItems,
                'mostReviewedItems' => $mostReviewedItems,
                'mostLikedItems' => $mostLikedItems,
            );
        }
    }
}