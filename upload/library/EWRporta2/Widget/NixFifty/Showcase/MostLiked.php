<?php

class EWRporta2_Widget_NixFifty_Showcase_MostLiked extends XenForo_Model
{
    public function getCachedData($widget, $options)
    {
        /** @var NFLJ_Showcase_Model_Item $scItemModel */
        $scItemModel = $this->getModelFromCache('NFLJ_Showcase_Model_Item');
        /** @var NFLJ_Showcase_Model_Category $scCategoryModel */
        $scCategoryModel = $this->getModelFromCache('NFLJ_Showcase_Model_Category');
        $visitor = XenForo_Visitor::getInstance();

        $mostLikedItems = array();

        if ($visitor['permissions']['nfljsc']['viewShowcase'])
        {
            $categoryIds = empty($options['nixfifty_showcase_mostliked_category']) ? array(): $options['nixfifty_showcase_mostliked_category'];

            // These are the cat id's that the viewing user is allowed to "view"
            $viewableCategories = $scCategoryModel->getViewableCategories();
            $viewableCategories = array_keys($viewableCategories);

            // These are the category ID's that are contained in BOTH arrays (to ensure the user only see's content they have permission to view)
            $catIds = array_intersect($categoryIds, $viewableCategories);

            $conditions = array(
                'category_id' => $catIds
            );

            $mostLikedItems = $scItemModel->getItems($conditions, array(
                'join' => NFLJ_Showcase_Model_Item::FETCH_CATEGORY
                    | NFLJ_Showcase_Model_Item::FETCH_USER,
                'limit' => $options['nixfifty_showcase_mostliked_limit'],
                'order' => 'liked',
                'direction' => 'DESC'
            ));

            $includeAttachments = $options['nixfifty_showcase_mostliked_attachments'] ? true : false;

            $mostLikedItems = $scItemModel->prepareItems($mostLikedItems, $includeAttachments);
        }

        return array(
            'mostLikedItems' => $mostLikedItems,
            'displayImages' => $options['nixfifty_showcase_mostliked_attachments']
        );
    }
}