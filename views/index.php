<div class="row">
	<div class="col-md-8">
		<div id="headlines-carousel" class="carousel slide">
			<ol class="carousel-indicators">
				<li data-target="#headlines-carousel" data-slide-to="0" class="active"></li>
				<li data-target="#headlines-carousel" data-slide-to="1"></li>
				<li data-target="#headlines-carousel" data-slide-to="2"></li>
			</ol>

			<div class="carousel-inner">
				<div class="item active">
					<a href="#null">
						<img src="./images/news/mario-kart-8-750x400.jpg" alt="Headlines news image">
						<div class="carousel-caption">
							<h2>Mario Kart 8: a Wii U seller?</h2>
							<h3>Well, let's review it!</h3>
						</div>
					</a>
				</div>
				<div class="item">
					<a href="#null">
						<img src="./images/news/watch-dogs-750x400.jpg" alt="Headlines news image">
						<div class="carousel-caption">
							<h2>Watch Dogs now avaible</h2>
							<h3>What has changed since E3 2012?</h3>
						</div>
					</a>
				</div>
				<div class="item">
					<a href="#null">
						<img src="./images/news/xbox-one-750x400.jpg" alt="Headlines news image">
						<div class="carousel-caption">
							<h2>An Xbox One pack for 399$</h2>
							<h3>You probably guessed it's without Kinect.</h3>
						</div>
					</a>
				</div>
			</div>

			<a class="left carousel-control" href="#headlines-carousel" data-slide="prev">
				<span class="glyphicon glyphicon-chevron-left"></span>
			</a>
			<a class="right carousel-control" href="#headlines-carousel" data-slide="next">
				<span class="glyphicon glyphicon-chevron-right"></span>
			</a>
		</div>
	</div>

	<div class="col-md-4">
		<h1><?php echo $clauses->get('hey_folks'); ?></h1>
		<?php echo stripslashes(eval($clauses->getMagic('index_text'))); ?>
	</div>
</div>

<div class="row">
	<div class="col-md-8 news-list">
		<h2><?php echo $clauses->get('latest_news'); ?></h2>

		<div class="col-lg-12 important-news">
			<a href="#null">
				<span class="sprites hotThumbLow"></span>
				<img data-original="./images/news/ios-8-os-x-10.10-750x100.png" alt="Big thumbnail for a news">
				<div class="mask"></div>
				<h3>iOS 8 and OS X Yosemite unveiled: new features, design & more</h3>
				<h4>They've never been so close!<small> — <?php echo Basics\Dates::sexyDate('2014-07-18', true, true) . ' ' . $clauses->get('at') . ' ' . Basics\Dates::sexyTime('2014-07-18 16:15:58'); ?> <span class="badge">42 <span class="glyphicon glyphicon-comment"></span></span></small></h4>
			</a>
		</div>

		<div class="col-lg-12 news no-padding">
			<a href="#null">
				<img data-original="./images/news/super-smash-bros-for-nintendo-3ds-and-wii-u-250x100.png" alt="Thumbnail for a news">
				<h3>Super Smash Bros. for Wii U will use NFC tags</h3>
				<h4>Physical DLCs ?!<small> — <?php echo Basics\Dates::sexyDate('2014-05-27', true, true) . ' ' . $clauses->get('at') . ' ' . Basics\Dates::sexyTime('2014-05-27 8:26:12'); ?> <span class="badge">18 <span class="glyphicon glyphicon-comment"></span></span></small></h4>
			</a>
		</div>
	</div>

	<div class="col-md-4">
		<h2><a href="<?php echo $linksDir; ?>polls/" title="<?php echo $clauses->get('show_more'); ?>"><?php echo $clauses->get('poll'); ?> »</a></h2>
		<div class="well poll-sidebar">
<?php
			if ($poll) {
?>
				<blockquote>
					<?php echo $poll['question']; ?>
					<small class="pull-right poll-participants"><?php echo Basics\Strings::plural($clauses->get('participants'), $poll['total_votes']); ?></small>
				</blockquote>

				<?php Basics\Templates::pollAnswers($poll); ?>

				<a href="<?php echo $linksDir . 'polls/' . $poll['id']; ?>"><?php echo $clauses->get('more'); ?> »</a>
<?php
			}
			else
				echo $clauses->get('no_poll_sidebar');
?>
		</div>

		<h2><?php echo $clauses->get('featured_content'); ?></h2>
		<ul class="nav nav-tabs top-content">
			<li class="active"><a href="#views" data-toggle="tab"><?php echo $clauses->get('most_read'); ?></a></li>
			<li><a href="#comments" data-toggle="tab"><?php echo $clauses->get('most_comment'); ?></a></li>
			<li><a href="#date" data-toggle="tab"><?php echo $clauses->get('latest_featured'); ?></a></li>
		</ul>
		<div class="tab-content well top-content">
			<div class="tab-pane fade active in" id="views">
				<span class="label label-default">1.102 <span class="glyphicon glyphicon-eye-open"></span></span>
				<a href="#null">E3 2014 : The Sims 4 avaible in fall</a>
				<hr>

				<span class="label label-default">862 <span class="glyphicon glyphicon-eye-open"></span></span>
				<a href="#null">E3 2014 : a beta for Fable Legends</a>
				<hr>

				<span class="label label-default">746 <span class="glyphicon glyphicon-eye-open"></span></span>
				<a href="#null">E3 2014 : Evolve gameplay demo</a>
				<hr>

				<span class="label label-default">524 <span class="glyphicon glyphicon-eye-open"></span></span>
				<a href="#null">Notch's new game</a>
				<hr>

				<span class="label label-default">517 <span class="glyphicon glyphicon-eye-open"></span></span>
				<a href="#null">E3 2014 : Battlefield Hardline beta avaible</a>
			</div>
			<div class="tab-pane fade" id="comments">
				<span class="label label-default">67 <span class="glyphicon glyphicon-comment"></span></span>
				<a href="#null">E3 2014 : Battlefield Hardline beta avaible</a>
				<hr>

				<span class="label label-default">58 <span class="glyphicon glyphicon-comment"></span></span>
				<a href="#null">E3 2014 : a beta for Fable Legends</a>
				<hr>

				<span class="label label-default">42 <span class="glyphicon glyphicon-comment"></span></span>
				<a href="#null">Notch's new game</a>
				<hr>

				<span class="label label-default">23 <span class="glyphicon glyphicon-comment"></span></span>
				<a href="#null">E3 2014 : Evolve gameplay demo</a>
				<hr>

				<span class="label label-default">20 <span class="glyphicon glyphicon-comment"></span></span>
				<a href="#null">E3 2014 : The Sims 4 avaible in fall</a>
			</div>
			<div class="tab-pane fade" id="date">
				<span class="label label-default">18:26</span>
				<a href="#null">E3 2014 : The Sims 4 avaible in fall</a>
				<hr>

				<span class="label label-default">17:34</span>
				<a href="#null">E3 2014 : a beta for Fable Legends</a>
				<hr>

				<span class="label label-default">16:12</span>
				<a href="#null">E3 2014 : Evolve gameplay demo</a>
				<hr>

				<span class="label label-default">12:06</span>
				<a href="#null">Notch's new game</a>
				<hr>

				<span class="label label-default">9:35</span>
				<a href="#null">E3 2014 : Battlefield Hardline beta avaible</a>
			</div>
		</div>
	</div>
</div>