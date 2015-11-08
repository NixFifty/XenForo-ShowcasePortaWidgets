<?php

class EWRporta2_Widget_NixFifty_Showcase_RecentComments extends XenForo_Model
{
    public function getCachedData($widget, $options)
    {
        /** @var NFLJ_Showcase_Model_Comment $scCommentModel */
        $scCommentModel = $this->getModelFromCache('NFLJ_Showcase_Model_Comment');
        /** @var NFLJ_Showcase_Model_Category $scCategoryModel */
        $scCategoryModel = $this->getModelFromCache('NFLJ_Showcase_Model_Category');
        $visitor = XenForo_Visitor::getInstance();

        $recentComments = array();

        if ($visitor['permissions']['nfljsc']['viewShowcase'])
        {
            $categoryIds = empty($options['nixfifty_showcase_recentcomments_category']) ? array(): $options['nixfifty_showcase_recentcomments_category'];

            // These are the cat id's that the viewing user is allowed to "view"
            $viewableCategories = $scCategoryModel->getViewableCategories();
            $viewableCategories = array_keys($viewableCategories);

            // These are the category ID's that are contained in BOTH arrays (to ensure the user only see's content they have permission to view)
            $catIds = array_intersect($categoryIds, $viewableCategories);

            $conditions = array(
                'category_id' => $catIds
            );

            $recentComments = $scCommentModel->getRecentComments($options['nixfifty_showcase_recentcomments_limit'], $conditions);
        }

        return array(
            'recentComments' => $recentComments
        );
    }
}