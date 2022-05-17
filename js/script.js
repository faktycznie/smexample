( function() {

	const cookieConsent = function() {
		const cookieBtn = document.querySelector('.cookie-consent__button .btn');
		if( cookieBtn ) {
			const cookieContainer = document.querySelector('.cookie-consent');

			const setCookie = (name, value, days = 365, path = '/') => {
				const expires = new Date(Date.now() + days * 864e5).toUTCString()
				document.cookie = name + '=' + encodeURIComponent(value) + '; expires=' + expires + '; path=' + path
			}

			cookieBtn.addEventListener('click', function() {
				cookieContainer.remove();
				setCookie('smexample-consent', '1');
			});
		}
	}

	const contactForm = function() {
		const form = document.querySelector('.contact-form');
		if( form ) {

			jQuery(form).validate({
				debug: true,
				rules: {
					name: "required",
					email: {
						required: true,
						email: true
					},
					message: "required",
					consent: {
						required: true
					}
				},
				messages: {
					name: "To pole jest wymagane", //use language constants in fututre :)
					email: {
						required: "To pole jest wymagane",
						email: "Wpisz poprawny adres email"
					},
					message: "To pole jest wymagane",
					consent: "To pole jest wymagane"
				}
			});

			const contactBtn = document.querySelector('.contact-form__btn');

			contactBtn.addEventListener('click', function(event) {
				event.preventDefault();

				const messages = form.parentNode.querySelector('.message');
				if( messages ) messages.remove();

				const submitForm = function(form) {
					contactBtn.disabled = true;
					
					const ajaxURL = form.getAttribute('action');
					const formData = new FormData(form);
					const params = new URLSearchParams(formData);
	
					fetch(ajaxURL, {
						method: 'POST',
						body: params
					}).then(response => {
						return response.json();
					}).then(response => {
						let status = ( true === response.success ) ? 'success' : 'fail';
						const message = displayMessage(response.data, status);
						form.parentNode.prepend(message);
						form.reset();
						contactBtn.disabled = false;
					}).catch(err => { 
						console.log(err);
					});
				}

				const valid = jQuery(form).valid();
				if( valid ) {
					submitForm(form);
				}
			});

			const checkboxMore = document.querySelector('.checkbox__more');
			checkboxMore.addEventListener('click', function(event) {
				event.preventDefault();
				const desc = document.querySelector('.contact-form__consent');
				if( desc ) {
					desc.classList.toggle('contact-form__consent--active');
					this.classList.toggle('checkbox__more--active');
					const label = this.textContent;
					const dataLabel = this.getAttribute('data-lang');
					this.setAttribute('data-lang', label);
					this.textContent = dataLabel;
				}
			});
		}
	}

	const displayMessage = function( message, type ) {
		let div = document.createElement("div");
		div.classList.add('message', 'message--' + type);
		div.innerHTML = message;
		return div;
	}

	const menuMobile = function() {
		const mobileBtn = document.querySelector('.hamburger');
		if( mobileBtn ) {
			const menu = document.querySelector('.nav-menu__container');

			mobileBtn.addEventListener('click', function(event) {
				event.preventDefault();
				this.classList.toggle('hamburger--active');
				document.body.classList.toggle('menu-mobile');
				menu.classList.toggle('nav-menu__container--mobile');
				menu.classList.toggle('animate__animated');
				menu.classList.toggle('animate__bounceInDown');
			});
		}
	}

	const menuScroll = function() {
		let scrollpos = window.scrollY;
		let latestScroll = scrollpos;

		window.addEventListener('scroll', function() { //set class on scroll
			scrollpos = window.scrollY;

			if( scrollpos === 0 ) {
				latestScroll = 0;
				document.body.classList.remove('scroll-down');
				document.body.classList.remove('scroll-up');
			} else if( scrollpos > latestScroll ) { // down
				document.body.classList.remove('scroll-up');
				document.body.classList.add('scroll-down');
				latestScroll = scrollpos;
			} else { //up
				document.body.classList.remove('scroll-down');
				document.body.classList.add('scroll-up');
				latestScroll = scrollpos;
			}
		});

		//set smooth scroll
		const links = document.querySelectorAll('a[href^="#"]');
		let headerOffset = 100;
		if( links.length ) {
			for(let item of links) {
				const href = item.attributes.href.value.replace('#', '');
				if( href.length < 1 ) continue;
				const target = document.getElementById(href);
				if(target) {
					item.addEventListener('click', function(event) {
						event.preventDefault();
						
						let elementPosition = target.offsetTop;
						let offsetPosition = elementPosition - headerOffset;

						window.scrollTo({
							top: offsetPosition,
							behavior: "smooth"
						});

						if(document.body.classList.contains('menu-mobile') && item.parentNode.classList.contains('menu-item')) {
							const mobileBtn = document.querySelector('.hamburger');
							mobileBtn.click(); //need to close mobile menu
						}
					});
				}
			}
		}
	}

	const ajaxReports = function() {
		const reportBtn = document.querySelectorAll('.reports__link');
		if( reportBtn.length ) {

			const setActive = el => {
				[...el.parentElement.children].forEach(sib => sib.classList.remove('reports__item--active'));
				el.classList.add('reports__item--active');
			}

			reportBtn.forEach(function(link) {
				link.addEventListener("click", function(event) {
					event.preventDefault();

					setActive(this.parentNode);
	
					const content = document.querySelector('.reports__content');

					content.classList.add('reports__content--empty');
					content.classList.remove('animate__fadeIn');
					content.classList.remove('animate__animated');

					const data = {
						action: 'smexample_get_reports',
						target: this.attributes.href.value.replace('#report', '')
					}
					const params = new URLSearchParams(data);
	
					fetch(ajax.url, {
						method: 'POST',
						body: params
					}).then(function (response) {
						return response.text();
					}).then(response => {
							content.innerHTML = response;

							content.classList.remove('reports__content--empty');
							content.classList.add('animate__fadeIn');
							content.classList.add('animate__animated');
	
							const chart = content.querySelectorAll('.report__chart');
							if( chart.length ) {
								chart.forEach(function(ch) {
									setupChart(ch);
								});
							}
	
					}).catch(err => { 
						console.log(err);
					});
				});
			});
	
			reportBtn[0].click();
		}
	}
	
	const setupChart = function( chart ) {
		let dataSource = chart.getAttribute('data-source');
		dataSource = JSON.parse(dataSource);

		let dataDimm = chart.getAttribute('data-dimm');
		dataDimm = JSON.parse(dataDimm);

		let dataColor = chart.getAttribute('data-color');
		dataColor = JSON.parse(dataColor);

		let bars = function ( num ) {
			--num; //since we need less bars than data-dimm
			const barArr = [];
			for(let i = 0; i < num; i++ ) {
				const barColor = ( typeof dataColor[i] !== 'undefined' ) ? dataColor[i] : dataColor[0];
				const barItem = { type: 'bar', color: barColor };
				barArr.push(barItem);
			}
			return barArr;
		}

		let option = {
			legend: {},
			tooltip: {},
			dataset: {
				dimensions: dataDimm,
				source: dataSource
			},
			xAxis: { type: 'category' },
			yAxis: {},
			// Declare several bar series, each will be mapped
			// to a column of dataset.source by default.
			series: bars(dataDimm.length)
		};

		let myChart = echarts.init( chart );
		myChart.setOption(option);
		window.onresize = function() {
			myChart.resize();
		};
	}

	document.addEventListener("DOMContentLoaded",function(){
		cookieConsent();
		contactForm();
		menuMobile();
		menuScroll();
		ajaxReports();
	});

}() );