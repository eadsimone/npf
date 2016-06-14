<?php
/**
 * Template Name: Full-width slider not title
 *
 * This is the template that displays full width page without sidebar
 *
 * @package sparkling
 */

get_header(); ?>

  <div id="primary" class="content-area">

    <main id="main" class="site-main" role="main">

      <?php while ( have_posts() ) : the_post(); ?>

        <?php get_template_part( 'content', 'page' ); ?>

        <?php
          // If comments are open or we have at least one comment, load up the comment template
          if ( get_theme_mod( 'sparkling_page_comments', 1 ) == 1 ) :
            if ( comments_open() || '0' != get_comments_number() ) :
              comments_template();
            endif;
          endif;
        ?>

      <?php endwhile; // end of the loop. ?>

    </main><!-- #main -->

  </div><!-- #primary -->

<div class="top-section logoslider">
  <?php echo do_shortcode( '[gs_logo]' ); ?>
</div>
<?php get_footer(); ?>

<script>
  $("h1.entry-title").addClass('hidden');



  $(document).ready(function() {
    /* set default */
    $( "#integratione" ).addClass( "show" );
    $( "#integration-panel-1" ).addClass('active');


    $(".integration-sectionone div.col-lg-3").mouseover(function() {
      $(this).find("h2").addClass( "whitechar" );
    });

    $(".integration-sectionone div.col-lg-3").mouseleave(function() {
      $( ".integration-sectionone div.col-lg-3 h2" ).removeClass( "whitechar" );
    });


    $( "#integration-panel-1" ).click(function() {

      $( "#integrationtwo" ).removeClass( "show" );
      $( "#integrationthree" ).removeClass( "show" );
      $( "#integrationfour" ).removeClass( "show" );

      $( "#integration-panel-2" ).removeClass( "active" );
      $( "#integration-panel-3" ).removeClass( "active" );
      $( "#integration-panel-4" ).removeClass( "active" );

      $( "#integratione" ).addClass( "show" );
      $("#integration-panel-1").addClass( "active" );
    });

    $( "#integration-panel-2" ).click(function() {

      $( "#integratione" ).removeClass( "show" );
      $( "#integrationthree" ).removeClass( "show" );
      $( "#integrationfour" ).removeClass( "show" );
      $( "#integration-panel-1" ).removeClass( "active" );
      $( "#integration-panel-3" ).removeClass( "active" );
      $( "#integration-panel-4" ).removeClass( "active" );

      $( "#integrationtwo" ).addClass( "show" );
      $("#integration-panel-2").addClass( "active" );
    });

    $( "#integration-panel-3" ).click(function() {

      $( "#integratione" ).removeClass( "show" );
      $( "#integrationtwo" ).removeClass( "show" );
      $( "#integrationfour" ).removeClass( "show" );

      $( "#integration-panel-1" ).removeClass( "active" );
      $( "#integration-panel-2" ).removeClass( "active" );
      $( "#integration-panel-4" ).removeClass( "active" );

      $( "#integrationthree" ).addClass( "show" );
      $("#integration-panel-3").addClass( "active" );
    });

    $( "#integration-panel-4" ).click(function() {


      $( "#integratione" ).removeClass( "show" );
      $( "#integrationtwo" ).removeClass( "show" );
      $( "#integrationthree" ).removeClass( "show" );

      $( "#integration-panel-1" ).removeClass( "active" );
      $( "#integration-panel-2" ).removeClass( "active" );
      $( "#integration-panel-3" ).removeClass( "active" );

      $( "#integrationfour" ).addClass( "show" );
      $("#integration-panel-4").addClass( "active" );
    });
    
//form pricing
    var visited=0;
    $('.selectplan').click(
        function(e) {
          e.preventDefault(); // prevent the default action
          e.stopPropagation(); // stop the click from bubbling
//          $(this).closest('ul').find('.selected').removeClass('selected');
//          $(this).parent().addClass('selected');

          var comparetorvalue=$(this).attr('id');

          if(visited==0) {
            $('#contacus-form-princing').toggleClass('hidden');            
            visited++;
          }
          $('#contacus-form-princing').attr('class', 'contacus-form-princing');
          if(comparetorvalue==="starter"){
            $('#contacus-form-princing').addClass('text-light-blue-bg');
            $('#mce-PLANTYPE').val('starter');
          }else if (comparetorvalue==="plus"){
            $('#contacus-form-princing').addClass('dark-blue-bg');
            $('#mce-PLANTYPE').val('plus');
          }else if(comparetorvalue==="pro"){
            $('#contacus-form-princing').addClass('violet-bg');
            $('#mce-PLANTYPE').val('pro');
          }else if(comparetorvalue==="enterprise"){
            $('#contacus-form-princing').addClass('light-green-bg');
            $('#mce-PLANTYPE').val('enterprise');
          }
        });    
    
  });

</script>