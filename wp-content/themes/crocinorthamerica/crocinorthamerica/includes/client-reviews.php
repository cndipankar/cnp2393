<?php $testimonials = get_option('otm_theme_options')['testimonials']; if ($testimonials): ?>
<div class="container reviews narrow px-0">
    <div class="testimonial-wrapper">
        <div class="content">
            <h2>Client Reviews</h2>
            <p>Dui id eu sit platea lacinia sit. Libero quam ut metus mollis sed tristique. Pretium dictumst pharetra eget
                risus.</p>
            <a class="txt-btn" href="#" title="View All Reviews">View all reviews</a>
        </div>
        <div class="slider">
            <div class="swiper-container testimonial-slider">
                <div class="swiper-wrapper">
                    <?php foreach ($testimonials as $testimonial): ?>
                    <div class="swiper-slide">
                        <div class="rating">
                            <?php for ($i = 0; $i < $testimonial['testimonial-rating']; $i++): ?>
                                <svg width="18" height="16" viewBox="0 0 18 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M17.4375 6.14727H10.9814L9 0.125L7.01859 6.14727H0.5625L5.84332 9.85273L3.78668 15.875L9 12.1428L14.2133 15.875L12.1528 9.85273L17.4375 6.14727Z" fill="#F9D371"/>
                                </svg>
                            <?php endfor; ?>
                        </div>
                        <p>"<?php echo $testimonial['testimonial'] ?>"</p>
                        <div class="author">
                            <?php if ($testimonial['testimonial-author']) { ?>
                            <h6><?php echo $testimonial['testimonial-author'] ?></h6>
                            <?php } ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <p class="p-lg-5 p-md-4 p-3 bg-white">Please add testimonials.</p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="slider-nav-wrapper">
            <div class="test-nav-prev slider-nav"><svg width="8" height="13" viewBox="0 0 8 13" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path d="M6.5 2L2 6.5L6.5 11" stroke="#E10915" stroke-width="2" stroke-miterlimit="10"
                    stroke-linecap="square" />
                </svg> Previous
            </div>
            <div class="test-nav-next slider-nav">Next <svg width="8" height="13" viewBox="0 0 8 13" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path d="M2 2L6.5 6.5L2 11" stroke="#E10915" stroke-width="2" stroke-miterlimit="10"
                    stroke-linecap="square" />
                </svg>
            </div>
        </div>
        </div>
    </div>
</div>