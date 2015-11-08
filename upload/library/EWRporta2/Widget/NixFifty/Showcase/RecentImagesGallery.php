<?php

class EWRporta2_Widget_NixFifty_Showcase_RecentImagesGallery extends XenForo_Model
{
    public function getCachedData($widget, $options)
    {
        /** @var NFLJ_Showcase_Model_Item $scItemModel */
        $scItemModel = $this->getModelFromCache('NFLJ_Showcase_Model_Item');
        /** @var NFLJ_Showcase_Model_Category $scCategoryModel */
        $scCategoryModel = $this->getModelFromCache('NFLJ_Showcase_Model_Category');
        /** @var NFLJ_Showcase_Model_Attachment $scAttachmentModel */
        $scAttachmentModel = $this->getModelFromCache('NFLJ_Showcase_Model_Attachment');
        $visitor = XenForo_Visitor::getInstance();

        $recentImages = array();

        if ($visitor['permissions']['nfljsc']['viewShowcase'])
        {
            $categoryIds = empty($options['nixfifty_showcase_recentimagesgallery_category']) ? array(): $options['nixfifty_showcase_recentimagesgallery_category'];

            // These are the cat id's that the viewing user is allowed to "view"
            $viewableCategories = $scCategoryModel->getViewableCategories();
            $viewableCategories = array_keys($viewableCategories);

            // These are the category ID's that are contained in BOTH arrays (to ensure the user only see's content they have permission to view)
            $catIds = array_intersect($categoryIds, $viewableCategories);

            $conditions = array(
                'content_type' => 'showcase_item',
                'category_id' => $catIds,
                'featured' => $options['nixfifty_showcase_recentimagesgallery_featured'] ? true : false
            );

            $fetchOptions = array(
                'limit' => $options['nixfifty_showcase_recentimagesgallery_limit'],
                'order' => 'recent',
                'join' => NFLJ_Showcase_Model_Attachment::FETCH_USER
                    | NFLJ_Showcase_Model_Attachment::FETCH_ITEM
                    | NFLJ_Showcase_Model_Attachment::FETCH_CATEGORY,
            );

            $recentImages = $scAttachmentModel->getShowcaseAttachments($conditions, $fetchOptions);

            $recentImages = $scItemModel->prepareItems($recentImages, false); // DO NOT change this!

            foreach ($recentImages as &$recentImage)
            {
                $recentImage = $scAttachmentModel->prepareAttachment($recentImage);
            }
        }

        return array(
            'recentImages' => $recentImages,
            'layout' => $options['nixfifty_showcase_recentimagesgallery_layout']
        );
    }
}
