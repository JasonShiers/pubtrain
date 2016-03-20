<h4><b>My Conference History</b></h4>
	<div class="conflist">
		<table style="width: 100%">
		<thead>
			<tr>
				<th class="left" style="width: 6em;">
					Start Date
				</th>
				<th>
					Title
				</th>
				<th>
					Location
				</th>
				<th style="width: 3em;">
					Days
				</th>
				<th class="right" style="width: 12em;">
					Feedback
				</th>
			</tr>
		</thead>
		<tbody>
			<?php
				foreach ($history as $h)
				{
					print("<tr>");
					print("<td>" . htmlspecialchars($h["confdate"]) . "</td>");
					print("<td>" . htmlspecialchars($h["title"]) . "</td>");
					print("<td>" . htmlspecialchars($h["location"]) . "</td>");
					print("<td>" . htmlspecialchars($h["days"]) . "</td>");
					if ($h["attended"]===null)
					{
						print("<td><a href=\"feedback.php?id=" . htmlspecialchars($h["req_id"]) . "\">Submit</td>");
					}
					else if ($h["attended"]==0)
					{
						print("<td>N/A</td>");
					}
					else
					{
						print("<td ><a style=\"font-weight: bold; color: forestgreen;\" href=\"feedbackreview.php?id=" . htmlspecialchars($h["req_id"]) 
							. "&userid=" . htmlspecialchars($_SESSION["userid"]) . "\">Review</a></td>");
					}
					print("</tr>");
				}
			?>
		</tbody>
	</table>
</div>
