<?php
	function abridge($str, $s, $l = null) {
		$len = strlen(utf8_decode($str));
		$temp = uc_substr($str, $s, $l);
		// 한두개 문자가 잘리우면 그대로 출력한다.
		if ($len < strlen(utf8_decode($temp)) + 2) return $str;
		// 3개 이상의 문자가 잘리우면 잘라서 출력한다.
		return $temp. "...";
	}
	function uc_substr($str, $s, $l = null) {
		return join("", array_slice(
			preg_split("//u", $str, -1, PREG_SPLIT_NO_EMPTY), $s, $l));
	}
	function sqlTextEncode($str) {
		$str = str_replace("'", "Ŏ", $str);
		$str = str_replace("\"", "Ő", $str);
		return $str;
	}
	function sqlTextDecode($str) {
		$str = str_replace("Ŏ", "'", $str);
		$str = str_replace("Ő", "\"", $str);
		return $str;
	}
	// get html of cell in calendar
	function getHtmlOfCalendarCell($year, $month, $day, $w, $boy, $girl) {
		$html = "<div class=''>";
		$html = $html . "<div style='text-align:left;width:70%;float:left;'>";
		
		$html = $html . "<p> &nbsp; 남 : " . $boy . "명</p>";
		$html = $html . "<p> &nbsp; 여 : " . $girl . "명</p>";
		$html = $html . "<p> &nbsp; 총 : " . ($boy + $girl) . "명</p>";
		$html = $html . "</div>";
		$html = $html . "<h3 style='text-align:right;width:20%;float:left;";
		if ((($day + $w - 1) % 7) == 0) {
			$html = $html . "color:#ff0000";
		}
		else {
			$html = $html . "color:#888888";
		}
		$html = $html . "'>";
		$html = $html . "<b>" . $day . "</b></h3>";

		$html = $html . "</div>";
		return $html;
	}
	// get calendar html from year and month
	function getHtmlVisitorOfMonth($year, $month, $count_mv, $count_wv) {
		$html = "";
		$w = date("w", mktime(0, 0, 0, $month, 1, $year));
		$d = -$w;
		$days = cal_days_in_month(CAL_GREGORIAN, $month, $year);

		for ($i = 0; $i < 48; $i = $i + 1) {
			if (($i % 8) == 0) {
				if ($d >= $days) break;
				$html = $html . "<tr>";
				$html = $html . "<td>" . ($i / 8 + 1) . "</td>";
				continue;
			}
			else {
				$d = $d + 1;
				
				if ($d > 0 && $d <= $days){
					$str = getHtmlOfCalendarCell($year, $month, $d, $w, $count_mv[$d], $count_wv[$d]);
					$html = $html . "<td date='" . $d . "' onclick='onClickedCalendarCell(this);'>". $str . "</td>";
				}
				else {
					$html = $html . "<td>&nbsp;</td>";
				}

				if (($i % 8) == 7) {
					$html = $html . "</tr>";
				}
			}
		}
		return $html;
	}
	// get html of visitor table per day
	function getHtmlVisitorOfDay($count_mv, $count_wv) {
		$html = "";
		$html = $html . "<thead><tr style='text-align:center'>";
		$html = $html . "<th style='text-align:center'>성별</th>";
		$html = $html . "<th style='text-align:center'>19살이하</th>";
		for ($i = 20; $i < 30; $i = $i + 1) {
			$html = $html . "<th style='text-align:center'>" .$i ."살</th>";
		}
		$html = $html . "</tr></thead>";
		$html = $html . "<tbody><tr style='text-align:center;height:40px;'>";
		$html = $html . "<td style='text-align:center'>남자</td>";
		for ($i = 19; $i < 30; $i = $i + 1) {
			$html = $html . "<td style='text-align:center'>". $count_mv[$i] . "</td>";
		}
		$html = $html . "</tr>";
		$html = $html . "<tr style='text-align:center;height:40px;'>";
		$html = $html . "<td style='text-align:center'>여자</td>";
		for ($i = 19; $i < 30; $i = $i + 1) {
			$html = $html . "<td style='text-align:center'>". $count_wv[$i] . "</td>";
		}
		$html = $html . "</tr></tbody>";
		$html = $html . "|||";
		$html = $html . "<thead><tr style='text-align:center'>";
		$html = $html . "<th style='text-align:center'>성별</th>";
		for ($i = 30; $i < 40; $i = $i + 1) {
			$html = $html . "<th style='text-align:center'>" .$i ."살</th>";
		}
		$html = $html . "<th style='text-align:center'>40살이상</th>";
		$html = $html . "</tr></thead>";
		$html = $html . "<tbody><tr style='text-align:center;height:40px;'>";
		$html = $html . "<td style='text-align:center'>남자</td>";
		for ($i = 30; $i < 41; $i = $i + 1) {
			$html = $html . "<td style='text-align:center'>". $count_mv[$i] . "</td>";
		}
		$html = $html . "</tr>";
		$html = $html . "<tr style='text-align:center;height:40px;'>";
		$html = $html . "<td style='text-align:center'>여자</td>";
		for ($i = 30; $i < 41; $i = $i + 1) {
			$html = $html . "<td style='text-align:center'>". $count_wv[$i] . "</td>";
		}
		$html = $html . "</tr></tbody>";
		return $html;
	}
	// get html of couples statistics table from year and month
	function getHtmlCouplesOfMonth($year, $month, $count_couples) {
		$html = "";
		$w = date("w", mktime(0, 0, 0, $month, 1, $year));
		$d = -$w;
		$days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
		$sum = 0;
		$sum_week = 0;

		for ($i = 0; $i < 48; $i = $i + 1) {
			if (($i % 8) == 0) {
				if ($d >= $days) break;
				$html = $html . "<tr style='height:60px;'>";
				$html = $html . "<td>" . ($i / 8 + 1) . "</td>";
				$sum_week = 0;
				continue;
			}
			else {
				$d = $d + 1;
				
				if ($d > 0 && $d <= $days){
					$html = $html . "<td date='" . $d . "'>";
					$html = $html . "<div style='width:100%;height:100%;'>";
					$html = $html . "<h3 style='text-align:right;width:90%;";
					if ((($d + $w - 1) % 7) == 0) {
						$html = $html . "color:#ff0000";
					}
					else if ((($d + $w - 1) % 7) == 6) {
						$html = $html . "color:#0000ff";
					}
					else {
						$html = $html . "color:#888888";
					}
					$html = $html . "'>";
					$html = $html . "<b>" . $d . "</b></h3>";
					$html = $html . "<div style='text-align:center;width:100%;'>";
					$html = $html . "<p>" . $count_couples[$d] . "</p>";
					$html = $html . "</div>";
					$html = $html . "</div>";
					$html = $html . "</td>";
					$sum_week += $count_couples[$d];
					$sum += $count_couples[$d];
				}
				else {
					$html = $html . "<td>&nbsp;</td>";
				}
				if (($i % 8) == 7) {
					$html = $html . "<td>$sum_week</td></tr>";
				}
			}
		}
		return $sum . "|||" . $html;
	}
?>