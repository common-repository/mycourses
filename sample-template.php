<?php
$courses = myc_get_courses();
?>

<ul class="courses">
	<li class="course header">
		<div class="date">Course Date</div>
		<div class="location">Location</div>
		<div class="in-class">In-Class Hours</div>
	</li>
	<?php $i = 1; foreach ($courses as $course): 
		if (date('Y', $course->start_date) == date('Y', $course->end_date)) {
			$start_year = '';
		}
		else {
			$start_year = ', ' . date('Y', $course->start_date);
		}
		$url = '/courses/register/?id=' . urlencode($course->id);
		?>
		<li class="course<?php echo ($i % 2 == 0) ? ' stripe' : ''; ?>">
			<div class="date"><?php $course->start_date('M jS') . $start_year ?> - <?php $course->end_date('M jS, Y') ?></div>
			<div class="location">
				<span class="name"><?php $course->location() ?></span>
				<address><?php $course->address() ?></address>
				<span class="map">(<a href="http://maps.google.com/maps?f=q&amp;hl=en&amp;q=<?php echo urlencode($course->location) ?>,+ON,+Canada">map</a>)</span>
			</div>
			<div class="in-class">
				<?php foreach ($course->course_times as $ct): ?>
				<div class="hours">
					<span class="weekday"><?php $ct->day() ?></span><span class="time"><?php $ct->start_time() ?> - <?php $ct->end_time() ?></span>
				</div>
				<?php endforeach; ?>
			</div>
			<a href="<?php echo $url ?>" class="register">Register</a>
		</li>
	<?php $i++; endforeach; ?>
</ul>
