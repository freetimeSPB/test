;(function ()
{
	BX.namespace('BX.Raduga');
	
	if (BX.Raduga.UserContacts)
	{
		return;
	}

	function UserContacts(params)
	{
	}
	
	UserContacts.prototype.init = function (params)
	{
		this.mainContainer=BX(params.mainContainerId);
		this.context = BX(params.containerId);
		//this.buttonShow=BX(params.buttonID);
		this.sendMessageBtn=BX(params.MessageId);
		this.uid=params.uid;
		this.url=params.url;
		this.wait=null;
		this.statusBlock=BX(params.statusId);
		this.loaded=false;
		this.sendMessageDiv=null;
		this.elementID=params.elementID;
		this.sendForm=null;
		this.message=null;
		
		//BX.bind(this.buttonShow, 'click', this.showBlock.bind(this));
		BX.bind(this.sendMessageBtn, 'click', this.getSendMessageForm.bind(this));
		this.refreshStatus();
	};
	
	UserContacts.prototype.showBlock = function (event)
	{
		BX.PreventDefault(event);
		
		BX.toggleClass(this.buttonShow, 'active');
		 if(BX.isNodeHidden(this.context)){
			 if(!this.loaded){
				 this.showWait();
				 var _this = this;
				 BX.ajax({   
						url: this.url,
						data: {
							'ajax_contacts': 'Y',
							'action': 'contacts',
							'uid': this.uid,
							'sessid': BX.bitrix_sessid()
						},
						method: 'POST',
						dataType: 'html',
						async: true,
						onsuccess: function(data){
							_this.setContent(data);
							
							if((typeof window['ym'])=='function')
								ym(55426021,'reachGoal','show_contacts');
						},
						onfailure: function(){
						
						}
					});
			 } else {
				 BX.show(this.context);
			 }	
		} else {
			BX.hide(this.context);
		}
	};
	
	UserContacts.prototype.getSendMessageForm = function (event)
	{
		BX.PreventDefault(event);
		
		this.showBigWait();
		
		if(!this.sendMessageDiv){
				this.sendMessageDiv=BX.create('div', {
				  attrs: {
					 className: 'popup popup-addclientm'
				  },
			   });
                 
				BX.append(this.sendMessageDiv, this.mainContainer);
		   
		} 
			var _this = this;
				BX.ajax({
						url: this.url,
						data: {
							'ajax_contacts': 'Y',
							'action': 'getSendMessageForm',
							'elementID': this.elementID,
							'uid': this.uid,
							'sessid': BX.bitrix_sessid()
						},
						method: 'POST',
						dataType: 'html',
						async: true,
						onsuccess: function(data){
							_this.setSendMessageData(data);
							
							if((typeof window['ym'])=='function')
								ym(55426021,'reachGoal','write_message');
						},
						onfailure: function(){
						
						}
					}); 
		if(!this.message){
				var _this = this;
				 BX.ajax({
						url: this.url,
						data: {
							'ajax_contacts': 'Y',
							'action': 'getmessagebox',
							'sessid': BX.bitrix_sessid()
						},
						method: 'POST',
						dataType: 'html',
						async: true,
						onsuccess: function(data){
							_this.createMessageBox(data);
						},
						onfailure: function(){
						
						}
					});
			}
	};
	
	UserContacts.prototype.refreshStatus = function ()
	{
		 if(!!this.statusBlock){
			 var _this = this;
			 BX.ajax({   
					url: this.url,
					data: {
						'ajax_contacts': 'Y',
						'action': 'status',
						'uid': this.uid,
						'sessid': BX.bitrix_sessid()
					},
					method: 'POST',
					dataType: 'json',
					async: true,
					onsuccess: function(data){
						if(data.status=='on'){
							BX.removeClass(_this.statusBlock, 'off');
							BX.addClass(_this.statusBlock, 'on');
							_this.statusBlock.innerHTML='ONLINE';
						}
					},
					onfailure: function(){
					
					}
				});
		 }
	};
	
	UserContacts.prototype.setSendMessageData = function (data)
	{
		this.sendMessageDiv.innerHTML=data;
		
		if((typeof window['FormsFunc'])=='function')
					FormsFunc();
		this.sendForm = BX.findChild(this.sendMessageDiv, {
									  tag: 'form'},true);
		if(!!this.sendForm)
    		BX.bind(this.sendForm, 'submit', BX.proxy(this.FormHandler, this));
		
		this.showPopup('addclientm');
		
	};
	
	UserContacts.prototype.showPopup = function (popup)
	{
		  this.hideBigWait();
	
			if((typeof window['popupshow'])=='function')
					popupshow(popup, false);
			
	};
		
	UserContacts.prototype.FormHandler = function (event){
	 BX.PreventDefault(event);

	  var strSerialized = BX.ajax.prepareForm(this.sendForm).data;
			 var _this = this;
		    this.showBigWait();
			
			BX.ajax({
					url: this.url,
					data: {
						'ajax_contacts': 'Y',
						'action': 'sendform',
						'objectData': strSerialized,
						'sessid': BX.bitrix_sessid()
					},
					method: 'POST',
					dataType: 'json',
					async: true,
					onsuccess: function(data){
						if(data.result=== 'ERROR'){
						   console.log(data.message);
						}else if (data.result === 'OK') {
							_this.showMessageBox();
						}
					},
					onfailure: function(){
					
					}
				}); 
	};
	
	UserContacts.prototype.createMessageBox = function (data){
			this.message=BX.create('div', {
				  attrs: {
					 className: 'popup popup-addclientm-alert ok'
				  },
			   });
			
			this.message.innerHTML=data;
	};
		
	UserContacts.prototype.showMessageBox = function (){	
           
		   if(!!this.message){
			   this.sendMessageDiv.innerHTML='';
		   }

		   var already = BX.findChild(this.mainContainer, {
									  tag: 'div', className: 'popup-addclientm-alert'},true);
			if(!already)
			     BX.append(this.message, this.mainContainer);
			
			this.showPopup('addclientm-alert');

	};
		
	UserContacts.prototype.setContent = function (data)
	{
		this.context.innerHTML=data;
		this.loaded=true;
		BX.show(this.context);
		this.hideWait();
	};
	
	
	UserContacts.prototype.showWait = function ()
	{
		if(!this.wait){
			this.wait=BX.create('div', {
			  attrs: {
				 id: 'hotel-wait'
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
			
		BX.insertAfter(this.wait, this.context);
		BX.show(this.wait);
	};
	
	UserContacts.prototype.showBigWait = function ()
	{
		if(!this.bigWait){
			
			this.bigWait=BX.create('div', {
			  attrs: {
				 id: 'hotel-big-wait'
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
			
		BX.insertAfter(this.bigWait, this.mainContainer);
		BX.show(this.bigWait);
	};
	
	UserContacts.prototype.hideWait = function ()
	{
		BX.hide(this.wait);
	};
	
	UserContacts.prototype.hideBigWait = function ()
	{
		BX.hide(this.bigWait);
	};
	
	BX.Raduga.UserContacts = new UserContacts();

})(window);


