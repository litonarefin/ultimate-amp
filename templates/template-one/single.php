<main id="content" role="main" class="">
	<article class="recipe-article">
		<header>
			<span class="ampstart-subtitle block px3 pt2 mb2">
                <?php //$uamp->load_parts( apply_filters( 'amp_post_template_meta_parts', array( 'meta-author', 'meta-time', 'meta-taxonomy' ) ) ); ?>
            </span>
			<h1 class="mb1 px3">
				<?php echo wp_kses_data( $uamp->get( 'post_title' ) ); ?>
            </h1>
			<!-- Start byline -->
			<address class="ampstart-byline clearfix mb4 px3 h5">
				<time class="ampstart-byline-pubdate block bold my1" datetime="2016-12-13">December 13, 2016</time>
			</address>
			<!-- End byline -->

            <?php do_action('uamp/template/featured-image');?>

		</header>
		<div class="mb4 px3">
			<?php echo $uamp->get( 'post_amp_content' ); // amphtml content; no kses ?>
        </div>

			<section class="recipe-comments">
				<h2 class="mb3">4 Responses</h2>
				<ul class="list-reset">
					<li class="mb4">
						<h3 class="ampstart-subtitle">Sriram</h3>
						<span class="h5 block mb3">02.24.17 at 6:01 pm</span>
						<p>This is perfect for a summer patio party. Thanks for another great one!</p>
					</li>
					<li class="mb4">
						<h3 class="ampstart-subtitle">Eric</h3>
						<span class="h5 block mb3">02.24.17 at 5:14 am</span>
						<p>These were so good I woke up dreaming about them. Regards, Eric.</p>
					</li>
				</ul>
			</section>
			<div class="ampstart-card max-width-1 pt2">
				<form method="GET" action="/" target="_top" class="p0 m0 px3 mb4">
					<fieldset class="border-none p0 m0">
						<h2 class="block mb4">Get our newsletter</h2>
						<!-- Start Input -->
						<div class="ampstart-input inline-block relative m0 p0 mb3 ">
							<input type="email" value="" name="emailid" id="emailid" class="block border-none p0 m0" placeholder="Email">
							<label for="emailid" class="absolute top-0 right-0 bottom-0 left-0" aria-hidden="true">Email</label>
						</div>
						<!-- End Input-->
						<!-- Start Submit -->
						<input type="submit" name="submit" value="SUBMIT" id="submit" class="ampstart-btn mb3 ampstart-btn-secondary">
						<!-- End Submit -->
					</fieldset>
				</form>
			</div>
			<section class="recipe-popular-articles">
				<h2 class="mb4">Popular Articles</h2>
				<amp-img src="../img/blog/popular1.jpg" width="1280" height="853" layout="responsive" alt="The sandy beach walk" class="mb1"></amp-img>
				<h3 class="mb4">Beach Getaway</h3>
				<amp-img src="../img/blog/popular2.png" width="649" height="497" layout="responsive" alt="Dinner on a plate" class="mb1"></amp-img>
				<h3 class="mb4">Dinner party tips</h3>
			</section>
			<section>
				<h2 class="mb4">Want</h2>
				<amp-carousel type="carousel" layout="fixed-height" height="285" controls="" class="mb4">
					<amp-img src="../img/blog/want1.png" width="205" height="285" alt="Want1"></amp-img>
					<amp-img src="../img/blog/want2.png" width="205" height="285" alt="Want2"></amp-img>
					<amp-img src="../img/blog/want3.png" width="205" height="285" alt="Want3"></amp-img>
					<amp-img src="../img/blog/want1.png" width="205" height="285" alt="Want1"></amp-img>
					<amp-img src="../img/blog/want2.png" width="205" height="285" alt="Want2"></amp-img>
					<amp-img src="../img/blog/want3.png" width="205" height="285" alt="Want3"></amp-img>
				</amp-carousel>
			</section>
			<section>
				<h2 class="mb4">Meet Lola</h2>
				<amp-img src="../img/blog/meetloloa.jpg" width="1280" height="853" layout="responsive" class="mb3" alt="Lola hanging out in the beach"></amp-img>
				<p class="mb4">
					Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Sed aliquam, nisi quis porttitor congue, elit erat euismod orci, ac placerat dolor lectus quis orci. Phasellus consectetuer vestibulum elit. Aenean tellus metus, bibendum sed, posuere ac, mattis non, nunc. Vestibulum fringilla pede sit amet augue. In turpis. Pellentesque posuere. Praesent turpis.
				</p>
			</section>
			<amp-instagram data-shortcode="BRJd8UIjTXN" width="379" height="379" layout="responsive" class="ampstart-card p0 mb4 px3"></amp-instagram>
			<ul class="ampstart-social-follow list-reset flex justify-around items-center flex-wrap m0 mb4">
				<li>
					<a href="#" target="_blank" class="inline-block p1" aria-label="Link to AMP HTML Twitter">
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="22.2" viewbox="0 0 53 49">
							<title>Twitter</title>
							<path d="M45 6.9c-1.6 1-3.3 1.6-5.2 2-1.5-1.6-3.6-2.6-5.9-2.6-4.5 0-8.2 3.7-8.2 8.3 0 .6.1 1.3.2 1.9-6.8-.4-12.8-3.7-16.8-8.7C8.4 9 8 10.5 8 12c0 2.8 1.4 5.4 3.6 6.9-1.3-.1-2.6-.5-3.7-1.1v.1c0 4 2.8 7.4 6.6 8.1-.7.2-1.5.3-2.2.3-.5 0-1 0-1.5-.1 1 3.3 4 5.7 7.6 5.7-2.8 2.2-6.3 3.6-10.2 3.6-.6 0-1.3-.1-1.9-.1 3.6 2.3 7.9 3.7 12.5 3.7 15.1 0 23.3-12.6 23.3-23.6 0-.3 0-.7-.1-1 1.6-1.2 3-2.7 4.1-4.3-1.4.6-3 1.1-4.7 1.3 1.7-1 3-2.7 3.6-4.6" class="ampstart-icon ampstart-icon-twitter"></path>
						</svg>
					</a>
				</li>
				<li>
					<a href="#" target="_blank" class="inline-block p1" aria-label="Link to AMP HTML Facebook">
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="23.6" viewbox="0 0 56 55">
							<title>Facebook</title>
							<path d="M47.5 43c0 1.2-.9 2.1-2.1 2.1h-10V30h5.1l.8-5.9h-5.9v-3.7c0-1.7.5-2.9 3-2.9h3.1v-5.3c-.6 0-2.4-.2-4.6-.2-4.5 0-7.5 2.7-7.5 7.8v4.3h-5.1V30h5.1v15.1H10.7c-1.2 0-2.2-.9-2.2-2.1V8.3c0-1.2 1-2.2 2.2-2.2h34.7c1.2 0 2.1 1 2.1 2.2V43" class="ampstart-icon ampstart-icon-fb"></path>
						</svg>
					</a>
				</li>
				<li>
					<a href="#" target="_blank" class="inline-block p1" aria-label="Link to AMP HTML Instagram">
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 54 54">
							<title>instagram</title>
							<path d="M27.2 6.1c-5.1 0-5.8 0-7.8.1s-3.4.4-4.6.9c-1.2.5-2.3 1.1-3.3 2.2-1.1 1-1.7 2.1-2.2 3.3-.5 1.2-.8 2.6-.9 4.6-.1 2-.1 2.7-.1 7.8s0 5.8.1 7.8.4 3.4.9 4.6c.5 1.2 1.1 2.3 2.2 3.3 1 1.1 2.1 1.7 3.3 2.2 1.2.5 2.6.8 4.6.9 2 .1 2.7.1 7.8.1s5.8 0 7.8-.1 3.4-.4 4.6-.9c1.2-.5 2.3-1.1 3.3-2.2 1.1-1 1.7-2.1 2.2-3.3.5-1.2.8-2.6.9-4.6.1-2 .1-2.7.1-7.8s0-5.8-.1-7.8-.4-3.4-.9-4.6c-.5-1.2-1.1-2.3-2.2-3.3-1-1.1-2.1-1.7-3.3-2.2-1.2-.5-2.6-.8-4.6-.9-2-.1-2.7-.1-7.8-.1zm0 3.4c5 0 5.6 0 7.6.1 1.9.1 2.9.4 3.5.7.9.3 1.6.7 2.2 1.4.7.6 1.1 1.3 1.4 2.2.3.6.6 1.6.7 3.5.1 2 .1 2.6.1 7.6s0 5.6-.1 7.6c-.1 1.9-.4 2.9-.7 3.5-.3.9-.7 1.6-1.4 2.2-.7.7-1.3 1.1-2.2 1.4-.6.3-1.7.6-3.5.7-2 .1-2.6.1-7.6.1-5.1 0-5.7 0-7.7-.1-1.8-.1-2.9-.4-3.5-.7-.9-.3-1.5-.7-2.2-1.4-.7-.7-1.1-1.3-1.4-2.2-.3-.6-.6-1.7-.7-3.5 0-2-.1-2.6-.1-7.6 0-5.1.1-5.7.1-7.7.1-1.8.4-2.8.7-3.5.3-.9.7-1.5 1.4-2.2.7-.6 1.3-1.1 2.2-1.4.6-.3 1.6-.6 3.5-.7h7.7zm0 5.8c-5.4 0-9.7 4.3-9.7 9.7 0 5.4 4.3 9.7 9.7 9.7 5.4 0 9.7-4.3 9.7-9.7 0-5.4-4.3-9.7-9.7-9.7zm0 16c-3.5 0-6.3-2.8-6.3-6.3s2.8-6.3 6.3-6.3 6.3 2.8 6.3 6.3-2.8 6.3-6.3 6.3zm12.4-16.4c0 1.3-1.1 2.3-2.3 2.3-1.3 0-2.3-1-2.3-2.3 0-1.2 1-2.3 2.3-2.3 1.2 0 2.3 1.1 2.3 2.3z" class="ampstart-icon ampstart-icon-instagram"></path>
						</svg>
					</a>
				</li>
				<li>
					<a href="#" target="_blank" class="inline-block p1" aria-label="Link to AMP HTML pin trest">
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="28.5" viewbox="0 0 43 51">
							<title>pinterest</title>
							<path d="M8.134 18.748c0-1.6.2-3 .8-4.4.5-1.4 1.2-2.6 2.2-3.6.9-1 2-1.9 3.2-2.6 1.2-.8 2.5-1.3 3.9-1.7 1.5-.4 2.9-.5 4.4-.5 2.2 0 4.3.4 6.2 1.4 1.9.9 3.5 2.3 4.7 4.1 1.2 1.9 1.8 3.9 1.8 6.2 0 1.4-.1 2.7-.4 4-.2 1.3-.7 2.6-1.2 3.8-.6 1.2-1.3 2.3-2.2 3.2-.8.9-1.8 1.7-3.1 2.2-1.2.6-2.5.9-4 .9-1 0-1.9-.3-2.9-.7-.9-.5-1.6-1.1-2-1.9-.1.5-.3 1.4-.6 2.4-.3 1.1-.4 1.7-.5 2-.1.3-.2.9-.4 1.6-.3.7-.4 1.2-.6 1.5-.1.3-.4.7-.7 1.3-.3.6-.6 1.2-1 1.7-.3.5-.7 1.1-1.3 1.8l-.3.1-.2-.2c-.2-2.2-.3-3.6-.3-4 0-1.3.2-2.8.5-4.4.3-1.7.8-3.7 1.4-6.2.6-2.5 1-3.9 1.1-4.4-.5-.9-.7-2.1-.7-3.6 0-1.2.4-2.3 1.1-3.3.8-1.1 1.7-1.6 2.8-1.6.9 0 1.6.3 2.1.9.4.6.7 1.3.7 2.2 0 .9-.3 2.3-1 4.1-.6 1.8-.9 3.1-.9 4 0 .9.3 1.6 1 2.2.6.6 1.4.9 2.3.9.8 0 1.5-.2 2.2-.5.6-.4 1.2-.9 1.6-1.5.5-.6.9-1.3 1.2-2 .4-.8.6-1.5.8-2.4.2-.8.4-1.6.5-2.4.1-.7.1-1.4.1-2.1 0-2.5-.8-4.4-2.3-5.8-1.6-1.4-3.6-2.1-6.1-2.1-2.8 0-5.2 1-7.1 2.8-1.9 1.9-2.9 4.2-2.9 7.1 0 .6.1 1.2.3 1.8.2.6.4 1.1.6 1.4.2.3.4.7.5 1 .2.3.3.5.3.6 0 .4-.1.9-.3 1.6-.2.6-.5 1-.8 1 0 0-.1-.1-.4-.1-.7-.2-1.3-.6-1.9-1.2-.5-.6-1-1.3-1.3-2-.3-.8-.5-1.6-.7-2.4-.2-.7-.2-1.5-.2-2.2z" class="ampstart-icon ampstart-icon-pinterest"></path>
						</svg>
					</a>
				</li>
			</ul>
			<section>
				<h2 class="mb3">Categories</h2>
				<ul class="list-reset p0 m0 mb4">
					<li class="mb2">
						<a href="#" class="text-decoration-none h3">Fashion</a>
					</li>
					<li class="mb2">
						<a href="#" class="text-decoration-none h3">Travel</a>
					</li>
					<li class="mb2">
						<a href="#" class="text-decoration-none h3">Decor</a>
					</li>
					<li class="mb2">
						<a href="#" class="text-decoration-none h3">Beauty</a>
					</li>
				</ul>
			</section>
		</section>
	</article>
</main>