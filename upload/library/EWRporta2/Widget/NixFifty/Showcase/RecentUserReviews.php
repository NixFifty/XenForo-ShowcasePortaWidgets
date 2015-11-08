<?php

class EWRporta2_Widget_NixFifty_Showcase_RecentUserReviews extends XenForo_Model
{
    public function getCachedData($widget, $options)
    {
        /** @var NFLJ_Showcase_Model_Item $scItemModel */
        $scItemModel = $this->getModelFromCache('NFLJ_Showcase_Model_Item');
        /** @var NFLJ_Showcase_Model_Category $scCategoryModel */
        $scCategoryModel = $this->getModelFromCache('NFLJ_Showcase_Model_Category');
        /** @var NFLJ_Showcase_Model_RateReview $scRateReviewModel */
        $scRateReviewModel = $this->getModelFromCache('NFLJ_Showcase_Model_RateReview');
        $visitor = XenForo_Visitor::getInstance();

        $recentUserReviews = array();

        if ($visitor['permissions']['nfljsc']['viewShowcase'])
        {
            $categoryIds = empty($options['nixfifty_showcase_recentuserreviews_category']) ? array(): $options['nixfifty_showcase_recentuserreviews_category'];

            // These are the cat id's that the viewing user is allowed to "view"
            $viewableCategories = $scCategoryModel->getViewableCategories();
            $viewableCategories = array_keys($viewableCategories);

            // These are the category ID's that are contained in BOTH arrays (to ensure the user only see's content they have permission to view)
            $catIds = array_intersect($categoryIds, $viewableCategories);

            $conditions = array(
                'category_id' => $catIds
            );

            $recentUserReviews = $scRateReviewModel->getRecentUserReviews($options['nixfifty_showcase_recentuserreviews_limit'], $conditions);
        }

        return array(
            'recentUserReviews' => $recentUserReviews,
            'displayTitle' => isset($options['nixfifty_showcase_recentuserreviews_options']['display_title']),
            'displayPros' => isset($options['nixfifty_showcase_recentuserreviews_options']['display_pros']),
            'displayCons' => isset($options['nixfifty_showcase_recentuserreviews_options']['display_cons']),
            'displaySummary' => isset($options['nixfifty_showcase_recentuserreviews_options']['display_summary']),
            'proconSnip' => $options['nixfifty_showcase_recentuserreviews_procon_snip'],
            'summarySnip' => $options['nixfifty_showcase_recentuserreviews_summary_snip'],
        );
	}
}
