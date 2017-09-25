<?php





function royalties3_get_quarters_options() {
	$quarters=array("1st", "2nd", "3rd", "4th");
	$years=array(date('Y')-1, date('Y'), date('Y')+1);

	$count_quarters=count($quarters);
	$count_years=count($years);

	for ($counter_years = 0; $counter_years < $count_years; $counter_years++)
	{
		$this_year=$years[$counter_years];

		for ($counter = 0; $counter < $count_quarters; $counter++)
		{
			$this_quarter=$quarters[$counter];

			$option_value=$this_year.' '.$this_quarter.' Qtr Royalties';

			$options .= "<option value='$option_value'>$option_value</option";
		}	
	}

	return $options;
}






