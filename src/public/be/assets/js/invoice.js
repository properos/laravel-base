/* Shivving (IE8 is not supported, but at least it won't look as awful)
/* ========================================================================== */

(function (document) {
	var
	head = document.head = document.getElementsByTagName('head')[0] || document.documentElement,
	elements = 'article aside audio bdi canvas data datalist details figcaption figure footer header hgroup mark meter nav output picture progress section summary time video x'.split(' '),
	elementsLength = elements.length,
	elementsIndex = 0,
	element;

	while (elementsIndex < elementsLength) {
		element = document.createElement(elements[++elementsIndex]);
	}

	element.innerHTML = 'x<style>' +
		'article,aside,details,figcaption,figure,footer,header,hgroup,nav,section{display:block}' +
		'audio[controls],canvas,video{display:inline-block}' +
		'[hidden],audio{display:none}' +
		'mark{background:#FF0;color:#000}' +
	'</style>';

	return head.insertBefore(element.lastChild, head.firstChild);
})(document);

/* Prototyping
/* ========================================================================== */

(function (window, ElementPrototype, ArrayPrototype, polyfill) {
	function NodeList() { [polyfill] }
	NodeList.prototype.length = ArrayPrototype.length;

	ElementPrototype.matchesSelector = ElementPrototype.matchesSelector ||
	ElementPrototype.mozMatchesSelector ||
	ElementPrototype.msMatchesSelector ||
	ElementPrototype.oMatchesSelector ||
	ElementPrototype.webkitMatchesSelector ||
	function matchesSelector(selector) {
		return ArrayPrototype.indexOf.call(this.parentNode.querySelectorAll(selector), this) > -1;
	};

	ElementPrototype.ancestorQuerySelectorAll = ElementPrototype.ancestorQuerySelectorAll ||
	ElementPrototype.mozAncestorQuerySelectorAll ||
	ElementPrototype.msAncestorQuerySelectorAll ||
	ElementPrototype.oAncestorQuerySelectorAll ||
	ElementPrototype.webkitAncestorQuerySelectorAll ||
	function ancestorQuerySelectorAll(selector) {
		for (var cite = this, newNodeList = new NodeList; cite = cite.parentElement;) {
			if (cite.matchesSelector(selector)) ArrayPrototype.push.call(newNodeList, cite);
		}

		return newNodeList;
	};

	ElementPrototype.ancestorQuerySelector = ElementPrototype.ancestorQuerySelector ||
	ElementPrototype.mozAncestorQuerySelector ||
	ElementPrototype.msAncestorQuerySelector ||
	ElementPrototype.oAncestorQuerySelector ||
	ElementPrototype.webkitAncestorQuerySelector ||
	function ancestorQuerySelector(selector) {
		return this.ancestorQuerySelectorAll(selector)[0] || null;
	};
})(this, Element.prototype, Array.prototype);

/* Helper Functions
/* ========================================================================== */

function generateTableRow() {
	var emptyColumn = document.createElement('tr');

	emptyColumn.innerHTML = '<td><a class="cut">-</a><span contenteditable class="item_name"></span></td>' +
		'<td><span contenteditable class="item_desc"></span></td>' +
		'<td><span data-prefix>$</span><span contenteditable  class="item_price">0.00</span></td>' +
		'<td><span contenteditable  class="item_qty">0</span></td>' +
		'<td><span data-prefix>$</span><span  class="item_subtotal">0.00</span></td>';

	return emptyColumn;
}

function parseFloatHTML(element) {
	return parseFloat(element.innerHTML.replace(/[^\d\.\-]+/g, '')) || 0;
}

function parsePrice(number) {
	return number.toFixed(2).replace(/(\d)(?=(\d\d\d)+([^\d]|$))/g, '$1,');
}

/* Update Number
/* ========================================================================== */

function updateNumber(e) {
	var
	activeElement = document.activeElement,
	value = parseFloat(activeElement.innerHTML),
	wasPrice = activeElement.innerHTML == parsePrice(parseFloatHTML(activeElement));

	if (!isNaN(value) && (e.keyCode == 38 || e.keyCode == 40 || e.wheelDeltaY)) {
		e.preventDefault();

		value += e.keyCode == 38 ? 1 : e.keyCode == 40 ? -1 : Math.round(e.wheelDelta * 0.025);
		value = Math.max(value, 0);

		activeElement.innerHTML = wasPrice ? parsePrice(value) : value;
	}

	updateInvoice();
}

/* Update Invoice
/* ========================================================================== */

function updateInvoice() {
	var total = 0;
	var cells, price, total, a, i;

	// update inventory cells
	// ======================

	for (var a = document.querySelectorAll('table.inventory tbody tr'), i = 0; a[i]; ++i) {
		// get inventory row cells
		cells = a[i].querySelectorAll('span:last-child');

		// set price as cell[2] * cell[3]
		price = parseFloatHTML(cells[2]) * parseFloatHTML(cells[3]);

		// add price to total
		total += price;

		// set row total
		cells[4].innerHTML = price;
	}
	// update balance cells
	// ====================

	// get balance cells
	cells = document.querySelectorAll('table.balance td:last-child span:last-child');

	// set total
	cells[0].innerHTML = total;

	// set balance and meta balance
	cells[2].innerHTML = document.querySelector('table.meta tr:last-child td:last-child span:last-child').innerHTML = parsePrice(total - parseFloatHTML(cells[1]));

	// update prefix formatting
	// ========================

	var prefix = document.querySelector('#prefix').innerHTML;
	for (a = document.querySelectorAll('[data-prefix]'), i = 0; a[i]; ++i) a[i].innerHTML = prefix;

	// update price formatting
	// =======================

	for (a = document.querySelectorAll('span[data-prefix] + span'), i = 0; a[i]; ++i) if (document.activeElement != a[i]) a[i].innerHTML = parsePrice(parseFloatHTML(a[i]));
}

/* On Content Load
/* ========================================================================== */

// function onContentLoad() {
// 	updateInvoice();

// 	var
// 	input = document.querySelector('input'),
// 	image = document.querySelector('img');

// 	function onClick(e) {
// 		var element = e.target.querySelector('[contenteditable]'), row;

// 		element && e.target != document.documentElement && e.target != document.body && element.focus();

// 		if (e.target.matchesSelector('.add')) {
// 			document.querySelector('table.inventory tbody').appendChild(generateTableRow());
// 		}
// 		else if (e.target.className == 'cut') {
// 			row = e.target.ancestorQuerySelector('tr');

// 			row.parentNode.removeChild(row);
// 		}

// 		updateInvoice();
// 	}

// 	function onEnterCancel(e) {
// 		e.preventDefault();

// 		image.classList.add('hover');
// 	}

// 	function onLeaveCancel(e) {
// 		e.preventDefault();

// 		image.classList.remove('hover');
// 	}

// 	function onFileInput(e) {
// 		image.classList.remove('hover');

// 		var
// 		reader = new FileReader(),
// 		files = e.dataTransfer ? e.dataTransfer.files : e.target.files,
// 		i = 0;

// 		reader.onload = onFileLoad;

// 		while (files[i]) reader.readAsDataURL(files[i++]);
// 	}

// 	function onFileLoad(e) {
// 		var data = e.target.result;

// 		image.src = data;
// 	}

// 	// if (window.addEventListener) {
// 	// 	document.addEventListener('click', onClick);

// 	// 	document.addEventListener('mousewheel', updateNumber);
// 	// 	document.addEventListener('keydown', updateNumber);

// 	// 	document.addEventListener('keydown', updateInvoice);
// 	// 	document.addEventListener('keyup', updateInvoice);

// 	// 	input.addEventListener('focus', onEnterCancel);
// 	// 	input.addEventListener('mouseover', onEnterCancel);
// 	// 	input.addEventListener('dragover', onEnterCancel);
// 	// 	input.addEventListener('dragenter', onEnterCancel);

// 	// 	input.addEventListener('blur', onLeaveCancel);
// 	// 	input.addEventListener('dragleave', onLeaveCancel);
// 	// 	input.addEventListener('mouseout', onLeaveCancel);

// 	// 	input.addEventListener('drop', onFileInput);
// 	// 	input.addEventListener('change', onFileInput);
// 	// }
// }

// window.addEventListener && document.addEventListener('DOMContentLoaded', onContentLoad);

window.payments = {
    driver(driver, paypal = false){
        var payment = {};
        if(driver.toLowerCase() == 'authorize'){
            payment['card'] = authorize;
        }else if(driver.toLowerCase() == 'stripe'){
            payment['card'] = stripe;
        }
        if(paypal){
            payment['paypal'] = true;
        }

        return payment;
    }

}

var authorize = {
    callBackAuthorize:({}),
    createCardToken(data, callBackAuthorize) {
        var authData = {};
            authData.clientKey = data.clientKey;
            authData.apiLoginID = data.apiLoginID;
        var cardData = {};
            cardData.cardNumber = data.card_number;
            cardData.month = data.exp_month;
            cardData.year = data.exp_year;
            cardData.cardCode = data.cvv;
            cardData.zip = data.billing_zip;
            cardData.fullName = data.billing_firstname+' '+data.billing_lastname;
        var secureData = {};
            secureData.authData = authData;
            secureData.cardData = cardData;
        
        this.callBackAuthorize = callBackAuthorize;
        // console.log(this.callBackAuthorize);

        Accept.dispatchData(secureData, this.responseHandler);
    },
    responseHandler(response){
        if (response.messages.resultCode === "Error") {
            authorize.callBackAuthorize({
                        status: 0,
                        code: '006',
                        errors: response.messages.message
                    });
        } else {
            authorize.callBackAuthorize({
                status: 1,
                data:{
                    token: response.opaqueData.dataValue,
                    description: response.opaqueData.dataDescriptor
                },
                message:''
            });
        }
    }
}

var stripe = {
    callBackStripe:({}),
    // data:{},
    createCardToken(data, callBackStripe) {
        this.callBackStripe = callBackStripe;
        // this.data = data;
        Stripe.card.createToken({
            number: data.card_number,
            cvc: data.cvv,
            exp_month: data.exp_month,
            exp_year: data.exp_year,
            address_zip: data.billing_zip,
            address_line1: data.billing_address, 
            address_city: data.billing_city, 
            address_state: data.billing_state, 
          }, this.stripeResponseHandler);

    },
    stripeResponseHandler(status, response){
        if (status != 200) {
            stripe.callBackStripe({
                        status: 0,
                        code: '006',
                        errors: response.error.message
                    });
        } else {
            stripe.callBackStripe({
                status: 1,
                data:{
                    token: response.id,
                    // description: stripe.data.description
                },
                message:''
            });
        }
    }
}
