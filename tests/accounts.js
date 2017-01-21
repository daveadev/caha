"use strict";
define(['model'], function($model) {
    return new $model({
        meta: {
            title: 'Accounts',
        },
        data: [{
                "account_no": "S201512345",
                "account_type": "student",
                "name": "Juan Dela Cruz Masipag Jr",
                "account_name": "Juan Dela Cruz Masipag Jr",
                "payment_scheme": "installment",
                "outstanding_balance": 6000,
				//"back_log":"Hello",
                "payment_breakdown": [{
                        "fee": "Tuition Fee",
                        "amount": 5000,
                        "paid": 1000,
                        "balance": 4000
                    },
                    {
                        "fee": "Matriculation Fee",
                        "amount": 4000,
                        "paid": 2000,
                        "balance": 2000
                    },
                    {
                        "fee": "Miscellaneous Fee",
                        "amount": 3000,
                        "paid": 3000,
                        "balance": 0
                    }
                ],
                "payment_schedule": [{
                        "billing_period": "June 2015",
                        "date_due": "June 15, 2015",
                        "amount_due": 4000
                    },
                    {
                        "billing_period": "July 2015",
                        "date_due": "July 16, 2015",
                        "amount_due": 4000
                    },
                    {
                        "billing_period": "August 2015",
                        "date_due": "August 17, 2015",
                        "amount_due": 4000
                    }
                ],
                "payment_history": [{
                        "date": "June 5,2015",
                        "ref_no": "OR-12345",
                        "details": "Initial Payment",
                        "amount": 3000
                    },
                    {
                        "date": "June 10,2015",
                        "ref_no": "OR-12346",
                        "details": "Subsequent Payment",
                        "amount": 3000
                    }
                ],
				"back_log" : [{
                        "transaction_no": 123456,
                        "ref_no": "OR-12345",
                        "amount": 3000
                    },
                    {
                        "transaction_no": 123456,
                        "ref_no": "OR-12346",
                        "amount": 3000
                    }
				
				
				]
            },
            {
                "account_no": 12346,
                "account_type": "student",
                "name": "Ted Philip Lat",
                "account_name": "Ted Philip Lat",
                "payment_scheme": "installment",
                "outstanding_balance": 5000,
                "payment_breakdown": [{
                        "fee": "Tuition Fee",
                        "amount": 5000,
                        "paid": 2000,
                        "balance": 3000
                    },
                    {
                        "fee": "Matriculation Fee",
                        "amount": 4000,
                        "paid": 2000,
                        "balance": 2000
                    },
                    {
                        "fee": "Miscellaneous Fee",
                        "amount": 3000,
                        "paid": 3000,
                        "balance": 0
                    }
                ],
                "payment_schedule": [{
                        "billing_period": "June 2015",
                        "date_due": "June 15, 2015",
                        "amount_due": 4000
                    },
                    {
                        "billing_period": "July 2015",
                        "date_due": "July 16, 2015",
                        "amount_due": 4000
                    },
                    {
                        "billing_period": "August 2015",
                        "date_due": "August 17, 2015",
                        "amount_due": 4000
                    }
                ],
                "payment_history": [{
                        "date": "June 5,2015",
                        "ref_no": "OR-12345",
                        "details": "Initial Payment",
                        "amount": 3500
                    },
                    {
                        "date": "June 10,2015",
                        "ref_no": "OR-12346",
                        "details": "Subsequent Payment",
                        "amount": 3500
                    }
                ]
            },
            {
                "account_no": 12346,
                "account_type": "student",
                "name": "Ted Philip Lat 2",
                "account_name": "Ted Philip Lat 2",
                "payment_scheme": "installment",
                "outstanding_balance": 5000,
                "payment_breakdown": [{
                        "fee": "Tuition Fee",
                        "amount": 5000,
                        "paid": 2000,
                        "balance": 3000
                    },
                    {
                        "fee": "Matriculation Fee",
                        "amount": 4000,
                        "paid": 2000,
                        "balance": 2000
                    },
                    {
                        "fee": "Miscellaneous Fee",
                        "amount": 3000,
                        "paid": 3000,
                        "balance": 0
                    }
                ],
                "payment_schedule": [{
                        "billing_period": "June 2015",
                        "date_due": "June 15, 2015",
                        "amount_due": 4000
                    },
                    {
                        "billing_period": "July 2015",
                        "date_due": "July 16, 2015",
                        "amount_due": 4000
                    },
                    {
                        "billing_period": "August 2015",
                        "date_due": "August 17, 2015",
                        "amount_due": 4000
                    }
                ],
                "payment_history": [{
                        "date": "June 5,2015",
                        "ref_no": "OR-12345",
                        "details": "Initial Payment",
                        "amount": 3500
                    },
                    {
                        "date": "June 10,2015",
                        "ref_no": "OR-12346",
                        "details": "Subsequent Payment",
                        "amount": 3500
                    }
                ]
            },
            {
                "account_no": 12346,
                "account_type": "student",
                "name": "Ted Philip Lat 3",
                "account_name": "Ted Philip Lat 3",
                "payment_scheme": "installment",
                "outstanding_balance": 5000,
                "payment_breakdown": [{
                        "fee": "Tuition Fee",
                        "amount": 5000,
                        "paid": 2000,
                        "balance": 3000
                    },
                    {
                        "fee": "Matriculation Fee",
                        "amount": 4000,
                        "paid": 2000,
                        "balance": 2000
                    },
                    {
                        "fee": "Miscellaneous Fee",
                        "amount": 3000,
                        "paid": 3000,
                        "balance": 0
                    }
                ],
                "payment_schedule": [{
                        "billing_period": "June 2015",
                        "date_due": "June 15, 2015",
                        "amount_due": 4000
                    },
                    {
                        "billing_period": "July 2015",
                        "date_due": "July 16, 2015",
                        "amount_due": 4000
                    },
                    {
                        "billing_period": "August 2015",
                        "date_due": "August 17, 2015",
                        "amount_due": 4000
                    }
                ],
                "payment_history": [{
                        "date": "June 5,2015",
                        "ref_no": "OR-12345",
                        "details": "Initial Payment",
                        "amount": 3500
                    },
                    {
                        "date": "June 10,2015",
                        "ref_no": "OR-12346",
                        "details": "Subsequent Payment",
                        "amount": 3500
                    }
                ]
            },
            {
                "account_no": 12346,
                "account_type": "student",
                "name": "Ted Philip Lat 4",
                "account_name": "Ted Philip Lat 4",
                "payment_scheme": "installment",
                "outstanding_balance": 5000,
                "payment_breakdown": [{
                        "fee": "Tuition Fee",
                        "amount": 5000,
                        "paid": 2000,
                        "balance": 3000
                    },
                    {
                        "fee": "Matriculation Fee",
                        "amount": 4000,
                        "paid": 2000,
                        "balance": 2000
                    },
                    {
                        "fee": "Miscellaneous Fee",
                        "amount": 3000,
                        "paid": 3000,
                        "balance": 0
                    }
                ],
                "payment_schedule": [{
                        "billing_period": "June 2015",
                        "date_due": "June 15, 2015",
                        "amount_due": 4000
                    },
                    {
                        "billing_period": "July 2015",
                        "date_due": "July 16, 2015",
                        "amount_due": 4000
                    },
                    {
                        "billing_period": "August 2015",
                        "date_due": "August 17, 2015",
                        "amount_due": 4000
                    }
                ],
                "payment_history": [{
                        "date": "June 5,2015",
                        "ref_no": "OR-12345",
                        "details": "Initial Payment",
                        "amount": 3500
                    },
                    {
                        "date": "June 10,2015",
                        "ref_no": "OR-12346",
                        "details": "Subsequent Payment",
                        "amount": 3500
                    }
                ]
            },
            {
                "account_no": 12346,
                "account_type": "student",
                "name": "Ted Philip Lat 5",
                "account_name": "Ted Philip Lat 5",
                "payment_scheme": "installment",
                "outstanding_balance": 5000,
                "payment_breakdown": [{
                        "fee": "Tuition Fee",
                        "amount": 5000,
                        "paid": 2000,
                        "balance": 3000
                    },
                    {
                        "fee": "Matriculation Fee",
                        "amount": 4000,
                        "paid": 2000,
                        "balance": 2000
                    },
                    {
                        "fee": "Miscellaneous Fee",
                        "amount": 3000,
                        "paid": 3000,
                        "balance": 0
                    }
                ],
                "payment_schedule": [{
                        "billing_period": "June 2015",
                        "date_due": "June 15, 2015",
                        "amount_due": 4000
                    },
                    {
                        "billing_period": "July 2015",
                        "date_due": "July 16, 2015",
                        "amount_due": 4000
                    },
                    {
                        "billing_period": "August 2015",
                        "date_due": "August 17, 2015",
                        "amount_due": 4000
                    }
                ],
                "payment_history": [{
                        "date": "June 5,2015",
                        "ref_no": "OR-12345",
                        "details": "Initial Payment",
                        "amount": 3500
                    },
                    {
                        "date": "June 10,2015",
                        "ref_no": "OR-12346",
                        "details": "Subsequent Payment",
                        "amount": 3500
                    }
                ]
            },
            {
                "account_no": 12346,
                "account_type": "student",
                "name": "Ted Philip Lat 6",
                "account_name": "Ted Philip Lat 6",
                "payment_scheme": "installment",
                "outstanding_balance": 5000,
                "payment_breakdown": [{
                        "fee": "Tuition Fee",
                        "amount": 5000,
                        "paid": 2000,
                        "balance": 3000
                    },
                    {
                        "fee": "Matriculation Fee",
                        "amount": 4000,
                        "paid": 2000,
                        "balance": 2000
                    },
                    {
                        "fee": "Miscellaneous Fee",
                        "amount": 3000,
                        "paid": 3000,
                        "balance": 0
                    }
                ],
                "payment_schedule": [{
                        "billing_period": "June 2015",
                        "date_due": "June 15, 2015",
                        "amount_due": 4000
                    },
                    {
                        "billing_period": "July 2015",
                        "date_due": "July 16, 2015",
                        "amount_due": 4000
                    },
                    {
                        "billing_period": "August 2015",
                        "date_due": "August 17, 2015",
                        "amount_due": 4000
                    }
                ],
                "payment_history": [{
                        "date": "June 5,2015",
                        "ref_no": "OR-12345",
                        "details": "Initial Payment",
                        "amount": 3500
                    },
                    {
                        "date": "June 10,2015",
                        "ref_no": "OR-12346",
                        "details": "Subsequent Payment",
                        "amount": 3500
                    }
                ]
            },
            {
                "account_no": 12346,
                "account_type": "student",
                "name": "Ted Philip Lat 7",
                "account_name": "Ted Philip Lat 7",
                "payment_scheme": "installment",
                "outstanding_balance": 5000,
                "payment_breakdown": [{
                        "fee": "Tuition Fee",
                        "amount": 5000,
                        "paid": 2000,
                        "balance": 3000
                    },
                    {
                        "fee": "Matriculation Fee",
                        "amount": 4000,
                        "paid": 2000,
                        "balance": 2000
                    },
                    {
                        "fee": "Miscellaneous Fee",
                        "amount": 3000,
                        "paid": 3000,
                        "balance": 0
                    }
                ],
                "payment_schedule": [{
                        "billing_period": "June 2015",
                        "date_due": "June 15, 2015",
                        "amount_due": 4000
                    },
                    {
                        "billing_period": "July 2015",
                        "date_due": "July 16, 2015",
                        "amount_due": 4000
                    },
                    {
                        "billing_period": "August 2015",
                        "date_due": "August 17, 2015",
                        "amount_due": 4000
                    }
                ],
                "payment_history": [{
                        "date": "June 5,2015",
                        "ref_no": "OR-12345",
                        "details": "Initial Payment",
                        "amount": 3500
                    },
                    {
                        "date": "June 10,2015",
                        "ref_no": "OR-12346",
                        "details": "Subsequent Payment",
                        "amount": 3500
                    }
                ]
            },
            {
                "account_no": 12346,
                "account_type": "student",
                "name": "Ted Philip Lat 8",
                "account_name": "Ted Philip Lat 8",
                "payment_scheme": "installment",
                "outstanding_balance": 5000,
                "payment_breakdown": [{
                        "fee": "Tuition Fee",
                        "amount": 5000,
                        "paid": 2000,
                        "balance": 3000
                    },
                    {
                        "fee": "Matriculation Fee",
                        "amount": 4000,
                        "paid": 2000,
                        "balance": 2000
                    },
                    {
                        "fee": "Miscellaneous Fee",
                        "amount": 3000,
                        "paid": 3000,
                        "balance": 0
                    }
                ],
                "payment_schedule": [{
                        "billing_period": "June 2015",
                        "date_due": "June 15, 2015",
                        "amount_due": 4000
                    },
                    {
                        "billing_period": "July 2015",
                        "date_due": "July 16, 2015",
                        "amount_due": 4000
                    },
                    {
                        "billing_period": "August 2015",
                        "date_due": "August 17, 2015",
                        "amount_due": 4000
                    }
                ],
                "payment_history": [{
                        "date": "June 5,2015",
                        "ref_no": "OR-12345",
                        "details": "Initial Payment",
                        "amount": 3500
                    },
                    {
                        "date": "June 10,2015",
                        "ref_no": "OR-12346",
                        "details": "Subsequent Payment",
                        "amount": 3500
                    }
                ]
            },
            {
                "account_no": 12346,
                "account_type": "student",
                "name": "Ted Philip Lat 9",
                "account_name": "Ted Philip Lat 9",
                "payment_scheme": "installment",
                "outstanding_balance": 5000,
                "payment_breakdown": [{
                        "fee": "Tuition Fee",
                        "amount": 5000,
                        "paid": 2000,
                        "balance": 3000
                    },
                    {
                        "fee": "Matriculation Fee",
                        "amount": 4000,
                        "paid": 2000,
                        "balance": 2000
                    },
                    {
                        "fee": "Miscellaneous Fee",
                        "amount": 3000,
                        "paid": 3000,
                        "balance": 0
                    }
                ],
                "payment_schedule": [{
                        "billing_period": "June 2015",
                        "date_due": "June 15, 2015",
                        "amount_due": 4000
                    },
                    {
                        "billing_period": "July 2015",
                        "date_due": "July 16, 2015",
                        "amount_due": 4000
                    },
                    {
                        "billing_period": "August 2015",
                        "date_due": "August 17, 2015",
                        "amount_due": 4000
                    }
                ],
                "payment_history": [{
                        "date": "June 5,2015",
                        "ref_no": "OR-12345",
                        "details": "Initial Payment",
                        "amount": 3500
                    },
                    {
                        "date": "June 10,2015",
                        "ref_no": "OR-12346",
                        "details": "Subsequent Payment",
                        "amount": 3500
                    }
                ]
            },
            {
                "account_no": 12346,
                "account_type": "student",
                "name": "Ted Philip Lat 10",
                "account_name": "Ted Philip Lat 10",
                "payment_scheme": "installment",
                "outstanding_balance": 5000,
                "payment_breakdown": [{
                        "fee": "Tuition Fee",
                        "amount": 5000,
                        "paid": 2000,
                        "balance": 3000
                    },
                    {
                        "fee": "Matriculation Fee",
                        "amount": 4000,
                        "paid": 2000,
                        "balance": 2000
                    },
                    {
                        "fee": "Miscellaneous Fee",
                        "amount": 3000,
                        "paid": 3000,
                        "balance": 0
                    }
                ],
                "payment_schedule": [{
                        "billing_period": "June 2015",
                        "date_due": "June 15, 2015",
                        "amount_due": 4000
                    },
                    {
                        "billing_period": "July 2015",
                        "date_due": "July 16, 2015",
                        "amount_due": 4000
                    },
                    {
                        "billing_period": "August 2015",
                        "date_due": "August 17, 2015",
                        "amount_due": 4000
                    }
                ],
                "payment_history": [{
                        "date": "June 5,2015",
                        "ref_no": "OR-12345",
                        "details": "Initial Payment",
                        "amount": 3500
                    },
                    {
                        "date": "June 10,2015",
                        "ref_no": "OR-12346",
                        "details": "Subsequent Payment",
                        "amount": 3500
                    }
                ]
            }
        ]
    }, { name: "Account" });
    //test.setMeta({title:'Test'});
    //test.setData([{title:'Sample','description':'dasd'}]);
    /*
    test.GET = function(){
    	return {success:test.list()};
    }
    test.POST = function(data){
    	return {success:test.save(data)};
    }
    */
});