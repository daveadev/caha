"use strict";
define(['model'],function($model){
	return new $model(
			{
			  "meta": {
				"class": "SystemDefault",
				"message": "List of System Defaults",
				"limit": 10,
				"next": null,
				"prev": null,
				"last": 1,
				"count": 8,
				"page": 1,
				"pages": 1,
				"epoch": 1456275986,
				"code": 200
			  },
			  "data": {
				"SCHOOL_NAME": "DAVE UNIVERSITY",
				"SCHOOL_ALIAS": "DU",
				"SCHOOL_LOGO": "logo.jpg",
				"SCHOOL_ADDRESS": "STO.TOMAS BATANGAS",
				"START_SY": 2013,
				"ACTIVE_SY": 2016,
				"SCHOOL_YEARS": [
				  {
					"id": 2013,
					"label": "2013-2014",
					"code": 13
				  },
				  {
					"id": 2014,
					"label": "2014-2015",
					"code": 14
				  },
				  {
					"id": 2015,
					"label": "2015-2016",
					"code": 15
				  },
				  {
					"id": 2016,
					"label": "2016-2017",
					"code": 16
				  }
				],
				"PERIODS": [
				  {
					"id": 1,
					"name": "First Grading",
					"alias": "1st"
				  },
				  {
					"id": 2,
					"name": "Second Grading",
					"alias": "2nd"
				  },
				  {
					"id": 3,
					"name": "Third Grading",
					"alias": "3rd"
				  },
				  {
					"id": 4,
					"name": "Fourth Grading",
					"alias": "4th"
				  }
				],
				"SPL_TRNX": {
				  "INTEREST": {
					"code": "INT",
					"flag": "+"
				  },
				  "DISCOUNT": {
					"code": "DSC",
					"flag": "-"
				  },
				  "CHARGE": {
					"code": "CHG",
					"flag": "+"
				  },
				  "PAYMENT": {
					"code": "PAY",
					"flag": "-"
				  }
				},
				"DEPARTMENTS": {
				  "PS": "Preschool",
				  "GS": "Grade School",
				  "HS": "High School",
				  "SH": "Senior High"
				},
				"YEAR_LEVELS": [
				  {
					"id": "G1",
					"name": "Grade 1",
					"alias": "G1",
					"educ_level_id": "GS"
				  },
				  {
					"id": "G2",
					"name": "Grade 2",
					"alias": "G2",
					"educ_level_id": "GS"
				  },
				  {
					"id": "G3",
					"name": "Grade 3",
					"alias": "G3",
					"educ_level_id": "GS"
				  },
				  {
					"id": "G4",
					"name": "Grade 4",
					"alias": "G4",
					"educ_level_id": "GS"
				  },
				  {
					"id": "G5",
					"name": "Grade 5",
					"alias": "G5",
					"educ_level_id": "GS"
				  },
				  {
					"id": "G6",
					"name": "Grade 6",
					"alias": "G6",
					"educ_level_id": "GS"
				  },
				  {
					"id": "G7",
					"name": "Grade 7",
					"alias": "G7",
					"educ_level_id": "HS"
				  },
				  {
					"id": "G8",
					"name": "Grade 8",
					"alias": "G8",
					"educ_level_id": "HS"
				  },
				  {
					"id": "G9",
					"name": "Grade 9",
					"alias": "G9",
					"educ_level_id": "HS"
				  },
				  {
					"id": "GX",
					"name": "Grade 10",
					"alias": "G10",
					"educ_level_id": "HS"
				  },
				  {
					"id": "GY",
					"name": "Grade 11",
					"alias": "G11",
					"educ_level_id": "SH"
				  },
				  {
					"id": "GZ",
					"name": "Grade 12",
					"alias": "G12",
					"educ_level_id": "SH"
				  }
				],
				"PROGRAMS": {
				  "REG": "Regular",
				  "PIL": "Pilot",
				  "SCI": "Science",
				  "STM": "STEM"
				}
			  }
			}
	);
});