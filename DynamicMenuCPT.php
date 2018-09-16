<?php
/**
*
*   
*
*
**/


/**
 *  Adding Dynamically complied menu links for BSJ to show previous months and years.
 *  
 *  Instructions:  
 *
 *  @param array $items         -   Wordpress menu item object
 *  @param obj $menu            -   Wordpress menu object
 *  @param string $menu_name    -   Wordpress menu slug name created under menu builder in settings. 
 *                                  Create the menu and use the name of that menu in this parameter.
 *  @param string $post_type    -   Wordpress custom post type
 *  @return array               -   wordpress array of items objects to place into the menu
 */

function cpt_Dynamic_nav_menu( $items, $menu, $menu_name="", $post_type="" ) {
    $CurrentDate = getDate(time());

    if ( $menu->slug == $menu_name ) {
        $top = _custom_nav_menu_item( 'Archive Pages', '/?post_type='.$post_type, 1 );

        $items[] = $top;

        // Loop for the monthly Archive links. Currently set for 6 months back
        for($i=1;$i<=6;$i++) {
            $yearNum = $CurrentDate['year'];
            $adjYearNum = $yearNum - 1;
            if(($CurrentDate['mon'] - $i) > 0){
                $adjMonthNum = $CurrentDate['mon'] - $i;
                $adjMonthName = month_name_Covert($adjMonthNum);
                $items[] = _custom_nav_menu_item(
                    "$adjMonthName, $yearNum Articles",
                    "/$yearNum/$adjMonthNum/?post_type=".$post_type,
                    100 + $i,
                    $top->ID);

            }else{
                $adjMonthNum = $CurrentDate['mon'] - $i +12;
                $adjMonthName = month_name_Covert($adjMonthNum);

                $items[] = _custom_nav_menu_item(
                    "$adjMonthName, $yearNum Articles",
                    "/$adjYearNum/$adjMonthNum/?post_type=".$post_type,
                    100 + $i,
                    $top->ID);
            }
        }

        // Yearly Archive Links
        $items[] = _custom_nav_menu_item( "$yearNum Articles", "/$yearNum/?post_type=".$post_type, 107, $top->ID );
        $items[] = _custom_nav_menu_item( "$adjYearNum Articles", "/$adjYearNum/?post_type=".$post_type, 108, $top->ID );
    }
    //var_dump($items);
    return $items;
}

add_filter( 'wp_get_nav_menu_items', 'cpt_Dynamic_nav_menu', 20, 2 );

/**
 * Simple helper function for make menu item objects
 *
 * @param $title      - menu item title
 * @param $url        - menu item url
 * @param $order      - where the item should appear in the menu
 * @param int $parent - the item's parent item
 * @return \stdClass
 */
function _custom_nav_menu_item( $title, $url, $order, $parent = 0 ){
    $item = new stdClass();
    $item->ID = 1000000 + $order + $parent;
    $item->db_id = $item->ID;
    $item->title = $title;
    $item->url = $url;
    $item->menu_order = $order;
    $item->menu_item_parent = $parent;
    $item->type = '';
    $item->object = '';
    $item->object_id = '';
    $item->classes = array();
    $item->target = '';
    $item->attr_title = '';
    $item->description = '';
    $item->xfn = '';
    $item->status = '';
    return $item;
}

/**
 *   Month number to month name method
 *
 *   @param  $monthNum   -   Month numbers as used in Wordpress native archive system
 *   @return \string   
**/

function month_name_Covert($monthNum){
    $month = "";
    switch ($monthNum) {
        case 1:
            $month = "January";
            break;
        case 2:
            $month = "Febuary";
            break;
        case 3:
            $month = "March";
            break;
        case 4:
            $month = "April";
            break;
        case 5:
            $month = "May";
            break;
        case 6:
            $month = "June";
            break;
        case 7:
            $month = "July";
            break;
        case 8:
            $month = "August";
            break;
        case 9:
            $month = "September";
            break;
        case 10:
            $month = "October";
            break;
        case 11:
            $month = "November";
            break;
        case 12:
            $month = "December";
            break;
    }return $month;
}
?>