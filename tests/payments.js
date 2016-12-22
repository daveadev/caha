"use strict";
define(['model', '../tests/ledgers', '../tests/booklets', '../tests/transactions', '../tests/accounts'], function($model) {

    var data = {
        meta: {
            title: 'Payments',
        },
        data: [

        ]
    };
    var registry = { name: "Payment", uses: ["ledgers", "booklets", "transactions", "accounts"] };
    var Payment = new $model(data, registry);
    Payment.POST = function(data) {
        console.log(data);
        var tDate = new Date();
        var monthNames = ["January", "February", "March", "April", "May", "June",
            "July", "August", "September", "October", "November", "December"
        ];
        var month = monthNames[tDate.getMonth()]
        var day = tDate.getDate();
        var year = tDate.getFullYear();
        var nDate = month + ' ' + day + ', ' + year;

        var booklet = DEMO_REGISTRY.Booklet[0];
        console.log(booklet);
        var ledger = {
            account: {
                id: data.student.id,
                account_name: data.student.name,
                account_type: "student"
            },
            type: "debit",
            date: nDate
        };


        var transaction = {
            type: "payment",
            status: "fulfilled",
            date: nDate,
            account: {
                account_no: data.student.id,
                account_name: data.student.name,
                account_type: "student"
            },
            transaction_details: [{

            }],
            transaction_payments: [{

            }]
        };
        console.log(transaction);
        var account = {
            account_no: data.student.id,
            account_type: "student",
            name: data.student.name,
            account_name: data.student.name,
            payment_scheme: "installment",
            //outstanding_balance: 6000
        };

        console.log(account);

        for (var i in data.transactions) {
            var trnx = data.transactions[i];

            var ledgers = DEMO_REGISTRY.Ledger;
            console.log(DEMO_REGISTRY);
            ledger.id = ledgers.length;
            ledger.ref_no = booklet.series_counter;
            ledger.details = trnx.id;
            ledger.amount = trnx.amount;
            DEMO_REGISTRY.Ledger.push(ledger);

            var transactions = DEMO_REGISTRY.Transaction;
            console.log(DEMO_REGISTRY);
            transaction.amount = trnx.amount;
            DEMO_REGISTRY.Transaction.push(transaction);

            var accounts = DEMO_REGISTRY.Account;
            console.log(DEMO_REGISTRY);
            DEMO_REGISTRY.Account.push(account);

        }
        booklet.series_counter++;
        DEMO_REGISTRY.Booklet[0] = booklet;
        return { success: Payment.save(data) };


    }

    return Payment;
});