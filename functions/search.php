<?php


function get_filter_form($options){ 
    
    ?>
    <form role="search" action="" method="post" id="searchform-find-a-show">
        <?php
        // grab and/or initialize $_POST variables
        if ( isset($_POST['search_tosearch']) )
            $toSearch = $_POST['search_tosearch'];
        else
            $toSearch = "";

        if ( isset($_POST['search_genre']) )
            $genre = $_POST['search_genre'];
        else
            $genre = "";

        if ( isset($_POST['search_city']) )
            $cityID = $_POST['search_city'];
        else
            $cityID = "";

        if ( isset($_POST['search_month']) )
            $month = $_POST['search_month']; // NOTE: allow for filtering over multiple months
        else
            $month = "";
        ?>
        <input type="hidden" name="search_post_type" value="shows" /> <!-- // hidden 'products' value -->
        <input type="hidden" name="search_tosearch" value="<?php $toSearch; ?>" />
        <div class="genre-filter">
            <input type="hidden" name="search_genre" value="<?php echo $genre; ?>" />
            <?php if(isset($options['genre']['label']) && $options['genre']['label'] !== ""){ ?>
            <h4>Filter by Genre</h4>
            <?php } ?>
            <?php
            // Alright, let's try fetching a list of genres that have "include_filter" set to true (or 1)
            $genreArgs = array(
                "taxonomy"		=>	"genre",
                "fields"		=>	"all",
                "meta_query"	=>	array(
                    array(
                        "key"		=> "include_filter",
                        "value"		=>	true,
                        "compare"	=>	"="
                    )
                )
            );
            $terms = get_terms( $genreArgs );
            ?>
            <select id="genre-filter" <?php echo (isset($options['genre']['multi'])) ? "multiple" : ""; ?>>
                <option value="">All Genres</option>
                <?php foreach( $terms as $term ) { ?>
                <option value="<?php echo $term->slug; ?>" <?php if($genre == $term->slug){ echo "selected='selected'"; }?>><?php echo $term->name; ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="city-filter">
            <input type="hidden" name="search_city" value="<?php echo $cityID; ?>" />
            <?php if(isset($options['city']['label']) && $options['city']['label'] !== ""){ ?>
            <h4>Filter by City</h4>
            
            <?php }
            // let get a list of all cities in the DB, build a selector for each one
            $cities = get_posts( array( "post_type" => "city" ) );
            ?>
            <select id="city-filter" <?php echo (isset($options['city']['multi'])) ? "multiple" : ""; ?>>
                <option value="">All Cities</option>
                <?php foreach( $cities as $city ) { ?>
                <option value="<?php echo $city->ID; ?>" <?php if($cityID == $city->ID) echo "selected='selected'"; ?>><?php echo $city->post_title; ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="month-filter">
            <input type="hidden" name="search_month" value="<?php echo $month; ?>" />
            <?php if(isset($options['month']['label']) && $options['month']['label'] !== ""){ ?>
            <h4>Filter by Month</h4>
            <?php } ?>
            <select class="month-filter" id="month-filter" size="13" <?php echo (isset($options['month']['multi'])) ? "multiple" : ""; ?>  >
                <img src="<?php echo get_template_directory_uri(); ?>/library/assets/icons/star-orange.png" class="filter-star" /><option value="" <?php if($month === ""){ echo "selected='selected'";} ?>>All Months</option>
                <img src="<?php echo get_template_directory_uri(); ?>/library/assets/icons/star-orange.png" class="filter-star" /><option value="0" <?php if($month === 0){ echo "selected='selected'";} ?>>January</option>
                <img src="<?php echo get_template_directory_uri(); ?>/library/assets/icons/star-orange.png" class="filter-star" /><option value="1" <?php if($month == 1){ echo "selected='selected'";} ?>>February</option>
                <img src="<?php echo get_template_directory_uri(); ?>/library/assets/icons/star-orange.png" class="filter-star" /><option value="2" <?php if($month == 2){ echo "selected='selected'";} ?>>March</option>
                <img src="<?php echo get_template_directory_uri(); ?>/library/assets/icons/star-orange.png" class="filter-star" /><option value="3" <?php if($month == 3){ echo "selected='selected'";} ?>>April</option>
                <img src="<?php echo get_template_directory_uri(); ?>/library/assets/icons/star-orange.png" class="filter-star" /><option value="4" <?php if($month == 4){ echo "selected='selected'";} ?>>May</option>
                <img src="<?php echo get_template_directory_uri(); ?>/library/assets/icons/star-orange.png" class="filter-star" /><option value="5" <?php if($month == 5){ echo "selected='selected'";} ?>>June</option>
                <img src="<?php echo get_template_directory_uri(); ?>/library/assets/icons/star-orange.png" class="filter-star" /><option value="6" <?php if($month == 6){ echo "selected='selected'";} ?>>July</option>
                <img src="<?php echo get_template_directory_uri(); ?>/library/assets/icons/star-orange.png" class="filter-star" /><option value="7" <?php if($month == 7){ echo "selected='selected'";} ?>>August</option>
                <img src="<?php echo get_template_directory_uri(); ?>/library/assets/icons/star-orange.png" class="filter-star" /><option value="8" <?php if($month == 8){ echo "selected='selected'";} ?>>September</option>
                <img src="<?php echo get_template_directory_uri(); ?>/library/assets/icons/star-orange.png" class="filter-star" /><option value="9" <?php if($month == 9){ echo "selected='selected'";} ?>>October</option>
                <img src="<?php echo get_template_directory_uri(); ?>/library/assets/icons/star-orange.png" class="filter-star" /><option value="10" <?php if($month == 10){ echo "selected='selected'";} ?>>November</option>
                <img src="<?php echo get_template_directory_uri(); ?>/library/assets/icons/star-orange.png" class="filter-star" /><option value="11" <?php if($month == 11){ echo "selected='selected'";} ?>>December</option>
            </select>
                            <script type="text/javascript">
                $('.month-selector').val(new Date().getMonth());
            </script>
        </div>
    </form>
<script>
    $('form#searchform-find-a-show select').change(adjustForm);
    var timeOutvar;
    function adjustForm(e){
        
        clearTimeout(timeOutvar);
        
        var val = e.target.value;
        var field = e.target.id.split('-')[0];
        
        $('form#searchform-find-a-show input[name="search_'+field+'"]').val(val);
        
        
        timeOutvar = setTimeout(function(){
            console.log("submitting");
            $('#searchform-find-a-show').submit();
        },2000)
    }
</script>
        <?php
}

?>