(function() {
	'use strict';

	if (!!window.JCCatalogAjaxLoadingComponent)
		return;

	window.JCCatalogAjaxLoadingComponent = function(params) {
	
		this.siteId = params.siteId || '';
		this.wait=null;
		this.templatePath = params.templatePath || '';
		this.container = document.querySelector('[data-entity="' + params.container + '"]');
		this.componentName = params.componentName || '';
		this.componentTemplate = params.componentTemplate || '';
		this.componentParams = params.componentParams || [];
		this.functionsAfterInit = params.functionsAfterInit || [];
		this.runTimeout = params.runTimeout || 0;
		this.onlyMobile = params.onlyMobile || 'N';
		this.onlyDesktop = params.onlyDesktop || 'N';
		this.allowLoad = true;
		
  	if(this.onlyMobile=="Y" || this.onlyDesktop=="Y"){
			this.width = BX.GetWindowInnerSize();
			
			if(this.onlyMobile=="Y" && this.width.innerWidth > 767){
				this.allowLoad = false;
			}
			if(this.onlyDesktop=="Y" && this.width.innerWidth < 767){
				this.allowLoad = false;
			}
		}
  
		if(this.allowLoad && this.componentName.length > 0 && this.componentTemplate.length > 0){
				     BX.ready(BX.delegate(this.loadTargetComponent, this));
		}
	};

	window.JCCatalogAjaxLoadingComponent.prototype =
	{
		loadTargetComponent: function()
		{
			if(this.runTimeout > 0){
				var _that=this;
				setTimeout(function(){
						_that.makeAjaxCall();	
					}, this.runTimeout);		
			} else {
				this.makeAjaxCall();
			}
               
		},
		
		makeAjaxCall: function()
		{
			var defaultData = {
					siteId: this.siteId,
					sessid: BX.bitrix_sessid(),
					get_loading_ajax: 'Y',
					name: this.componentName,
					template: this.componentTemplate,
					data: this.componentParams
				};
				
				 var _that=this;
				 
				 BX.ajax({
					url: this.templatePath + '/ajax.php',
					method: 'POST',
					dataType: 'html',
					//processData: true,
					//scriptsRunFirst: true,
                    //emulateOnload: true,
					timeout: 60,
					data: defaultData,
					onsuccess: BX.delegate(function(result){
						_that.container.innerHTML=result;
						_that.initJsFunctions();
					}, this)
				});
				
		},
		
		initJsFunctions: function()
		{
			if(this.functionsAfterInit.length > 0){
				var fname='';
				for (var l in this.functionsAfterInit)
				{
					fname=this.functionsAfterInit[l];
					
					if((typeof window[fname])=='function')
					   window[fname]();
				}
			}
		},
		
		showWait: function(){
			if(!this.wait){
				
				this.wait=BX.create('div', {
				  attrs: {
					 id: 'preheader-menu-wait'
				  },
				  children: [
						  BX.create('span', {
						  attrs: {
							 className: 'api-image'
						  }
					   }),
					   BX.create('span', {
						  attrs: {
							 className: 'api-bg'
						  }
					   })
				  ]
			   });
			}
				
			BX.insertAfter(this.wait, this.containerBody);
			BX.show(this.wait);
		},
		
		hideWait: function(){
			BX.hide(this.wait);
		},	
	};
})();