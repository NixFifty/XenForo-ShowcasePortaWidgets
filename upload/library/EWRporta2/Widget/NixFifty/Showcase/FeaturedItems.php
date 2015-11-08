<?php

class EWRporta2_Widget_NixFifty_Showcase_FeaturedItems extends XenForo_Model
{
    public function getCachedData($widget, $options)
    {
        /** @var NFLJ_Showcase_Model_Item $scItemModel */
        $scItemModel = $this->getModelFromCache('NFLJ_Showcase_Model_Item');
        /** @var NFLJ_Showcase_Model_Category $scCategoryModel */
        $scCategoryModel = $this->getModelFromCache('NFLJ_Showcase_Model_Category');
        $visitor = XenForo_Visitor::getInstance();

        $featuredItems = array();

        if ($visitor['permissions']['nfljsc']['viewShowcase'])
        {
            $categoryIds = empty($options['nixfifty_showcase_featureditems_category']) ? array(): $options['nixfifty_showcase_featureditems_category'];

            // These are the cat id's that the viewing user is allowed to "view"
            $viewableCategories = $scCategoryModel->getViewableCategories();
            $viewableCategories = array_keys($viewableCategories);

            // These are the category ID's that are contained in BOTH arrays (to ensure the user only see's content they have permission to view)
            $catIds = array_intersect($categoryIds, $viewableCategories);

            $conditions = array(
                'category_id' => $catIds,
                'featured' => true,
            );

            $featuredItems = $scItemModel->getItems($conditions, array(
                'join' => NFLJ_Showcase_Model_Item::FETCH_CATEGORY
                    | NFLJ_Showcase_Model_Item::FETCH_USER,
                'limit' => $options['nixfifty_showcase_featureditems_limit'],
                'order' => 'featured',
                'direction' => 'DESC'
            ));

            $includeAttachments = $options['nixfifty_showcase_featureditems_attachments'] ? true : false;

            $featuredItems = $scItemModel->prepareItems($featuredItems, $includeAttachments);
        }

        return array(
            'featuredItems' => $featuredItems,
            'displayImages' => $options['nixfifty_showcase_featureditems_attachments']
        );
	}
}