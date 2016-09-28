/**
 * http://github.com/valums/file-uploader
 * 
 * Multiple file upload component with progress-bar, drag-and-drop. 
 * © 2010 Andrew Valums andrew(at)valums.com 
 * 
 * Licensed under MIT, GNU GPL and GNU LGPL 2 or later.
 *  
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>. 
 */    
    
var qq = qq || {};

/**
 * Class that creates our multiple file upload widget
 */
qq.FileUploader = function(o){
    var l = o.labels, style = '';
    if(o.size.width)  style += 'width: ' + o.size.width;
    if(o.size.height) style += 'height: ' + o.size.height;
    this._options = {
        // container element DOM node (ex. $(selector)[0] for jQuery users)
        element: null,
        // url of the server-side upload script, should be on the same domain
        action: '/server/upload',
        // additional data to send, name-value pairs
        params: {},
        // ex. ['jpg', 'jpeg', 'png', 'gif'] or []
        allowedExtensions: [],        
        // size limit in bytes, 0 - no limit
        // this option isn't supported in all browsers
        sizeLimit: 0,
        onSubmit: function(id, fileName){},
        onComplete: function(id, fileName, responseJSON){},

        //
        // UI customizations

        template: '<div class="qq-uploader">' + 
                '<div class="qq-upload-drop-area" style="' + style +
                '"><table style="width: 100%; height: 100%;"><tr><td><div class="qq-label-drop">' +
                l.labelDrop + '</div>' +
                '<div class="qq-upload-button">' + l.labelButton + '</div></td></tr></table></div>' +
                '<ul class="qq-upload-list"></ul>' + 
             '</div>',

        // template for one item in file list
        fileTemplate: '<li>' +
                '<span class="qq-upload-file"></span>' +
                '<span class="qq-upload-spinner">&nbsp;</span>' +
                '<span class="qq-upload-size"></span>' +
                '<a class="qq-upload-cancel" href="#">' + l.labelCancel + '</a>' +
                '<span class="qq-upload-failed-text">' + l.labelFailed + '</span>' +
            '</li>',        

        classes: {
            // used to get elements from templates
            button: 'qq-upload-button',
            drop: 'qq-upload-drop-area',
            dropActive: 'qq-upload-drop-area-active',
            list: 'qq-upload-list',
            dragEnter: 'qq-drag-enter',
            labelDrop: 'qq-label-drop',
                        
            file: 'qq-upload-file',
            spinner: 'qq-upload-spinner',
            size: 'qq-upload-size',
            cancel: 'qq-upload-cancel',

            // added to list item when upload completes
            // used in css to hide progress spinner
            success: 'qq-upload-success',
            fail: 'qq-upload-fail'
        },
        messages: {
            //serverError: "Some files were not uploaded, please contact support and/or try again.",
            typeError: "{file} has invalid extension. Only {extensions} are allowed.",
            sizeError: "{file} is too large, maximum file size is {sizeLimit}.",
            emptyError: "{file} is empty, please select files again without it."            
        },
        showMessage: function(message){
            alert(message);
        }
    };

    qq.extend(this._options, o);       
    
    this._element = this._options.element;

    if (this._element.nodeType != 1){
        throw new Error('element param of FileUploader should be dom node');
    }
    
    this._element.innerHTML = this._options.template;
    
    // number of files being uploaded
    this._filesInProgress = 0;
    
    // easier access
    this._classes = this._options.classes;
    
    this._handler = this._createUploadHandler();    
    
    this._bindCancelEvent();
    
    var self = this;
    this._button = new qq.UploadButton({
        element: this._getElement('button'),
        multiple: qq.UploadHandlerXhr.isSupported(),
        onChange: function(input){
            self._onInputChange(input);
        }        
    });        
    
    this._setupDragDrop();
};

qq.FileUploader.prototype = {
    setParams: function(params){
        this._options.params = params;
    },
    /**
     * Returns true if some files are being uploaded, false otherwise
     */
    isUploading: function(){
        return !!this._filesInProgress;
    },  
    /**
     * Gets one of the elements listed in this._options.classes
     * 
     * First optional element is root for search,
     * this._element is default value.
     *
     * Usage
     *  1. this._getElement('button');
     *  2. this._getElement(item, 'file'); 
     **/
    _getElement: function(parent, type){                        
        if (typeof parent == 'string'){
            // parent was not passed
            type = parent;
            parent = this._element;                   
        }
        
        var element = qq.getByClass(parent, this._options.classes[type])[0];
        
        if (!element){
            throw new Error('element not found ' + type);
        }
        
        return element;
    },
    _error: function(code, fileName){
        var message = this._options.messages[code];
        message = message.replace('{file}', this._formatFileName(fileName));
        message = message.replace('{extensions}', this._options.allowedExtensions.join(', '));
        message = message.replace('{sizeLimit}', this._formatSize(this._options.sizeLimit));
        this._options.showMessage(message);                
    },
    _formatFileName: function(name){
        if (name.length > 33){
            name = name.slice(0, 19) + '...' + name.slice(-13);    
        }
        return name;
    },
    _isAllowedExtension: function(fileName){
        var ext = (-1 !== fileName.indexOf('.')) ? fileName.replace(/.*[.]/, '').toLowerCase() : '';
        var allowed = this._options.allowedExtensions;
        
        if (!allowed.length){return true;}        
        
        for (var i=0; i<allowed.length; i++){
            if (allowed[i].toLowerCase() == ext){
                return true;
            }    
        }
        
        return false;
    },
    _setupDragDrop: function(){
        function isValidDrag(e){            
            var dt = e.dataTransfer,
                // do not check dt.types.contains in webkit, because it crashes safari 4            
                isWebkit = navigator.userAgent.indexOf("AppleWebKit") > -1;                        

            // dt.effectAllowed is none in Safari 5
            // dt.types.contains check is for firefox            
            return dt && dt.effectAllowed != 'none' && 
                (dt.files || (!isWebkit && dt.types.contains && dt.types.contains('Files')));
        }
        
        var self = this,
            dropArea = this._getElement('drop'),
            labelDragEnter = this._getElement('labelDrop'),
            button = this._getElement('button');                        
        
        //dropArea.style.display = 'none'; mams827
        labelDragEnter.style.display = 'none';
        button.style.display = 'block';
        
        var hideTimeout;        
        qq.attach(document, 'dragenter', function(e){            
            e.preventDefault(); 
        });        

        qq.attach(document, 'dragover', function(e){
            if (isValidDrag(e)){
                         
                if (hideTimeout){
                    clearTimeout(hideTimeout);
                }

                qq.addClass(dropArea, self._classes.dragEnter);
                labelDragEnter.style.display = 'block';
                button.style.display = 'none';
                
                if (dropArea == e.target || qq.contains(dropArea,e.target)){
                    var effect = e.dataTransfer.effectAllowed;
                    if (effect == 'move' || effect == 'linkMove'){
                        e.dataTransfer.dropEffect = 'move'; // for FF (only move allowed)    
                    } else {                    
                        e.dataTransfer.dropEffect = 'copy'; // for Chrome
                    }                                                                                    
                    qq.addClass(dropArea, self._classes.dropActive);     
                    e.stopPropagation();                                                           
                } else {
                    //dropArea.style.display = 'block';
                    e.dataTransfer.dropEffect = 'none';    
                }
                                
                e.preventDefault();                
            }            
        });         
        
        qq.attach(document, 'dragleave', function(e){  
            if (isValidDrag(e)){
                                
                if (dropArea == e.target || qq.contains(dropArea,e.target)){                                        
                    qq.removeClass(dropArea, self._classes.dropActive);      
                    e.stopPropagation();                                       
                } else {
                                        
                    if (hideTimeout){
                        clearTimeout(hideTimeout);
                    }
                    
                    hideTimeout = setTimeout(function(){                                                
                        //dropArea.style.display = 'none';                            
                        labelDragEnter.style.display = 'none';
                        button.style.display = 'block';
                        qq.removeClass(dropArea, self._classes.dragEnter);
                    }, 10);
                }   
            }            
        });
        
        qq.attach(dropArea, 'drop', function(e){            
            //dropArea.style.display = 'none';
            labelDragEnter.style.display = 'none';
            button.style.display = 'block';
            qq.removeClass(dropArea, self._classes.dragEnter);
            self._uploadFileList(e.dataTransfer.files);            
            e.preventDefault();
        });                      
    },
    _createUploadHandler: function(){
        var self = this,
            handlerClass;        
        
        if(qq.UploadHandlerXhr.isSupported()){           
            handlerClass = 'UploadHandlerXhr';                        
        } else {
            handlerClass = 'UploadHandlerForm';
        }

        var handler = new qq[handlerClass]({
            action: this._options.action,            
            onProgress: function(id, fileName, loaded, total){
                // is only called for xhr upload
                self._updateProgress(id, loaded, total);                    
            },
            onComplete: function(id, fileName, result){
                self._filesInProgress--;

                // mark completed
                var item = self._getItemByFileId(id);                
                qq.remove(self._getElement(item, 'cancel'));
                qq.remove(self._getElement(item, 'spinner'));
                
                if (result.success){
                    qq.addClass(item, self._classes.success);    
                } else {
                    qq.addClass(item, self._classes.fail);
                    
                    if (result.error){
                       self._options.showMessage(result.error); 
                    }
                }
                    
                self._options.onComplete(id, fileName, result);                                
            }
        });

        return handler;
    },
    _onInputChange: function(input){

        if (this._handler instanceof qq.UploadHandlerXhr){     
            
            this._uploadFileList(input.files);       
            
        } else {
             
            if (this._validateFile(input)){                
                this._uploadFile(input);                                    
            }
                      
        }        
        
        this._button.reset();   
    },  
    _uploadFileList: function(files){
        var valid = true;

        var i = files.length;
        while (i--){         
            if (!this._validateFile(files[i])){
                valid = false;
                break;
            }
        }  
        
        if (valid){                                      
            var i = files.length;
            while (i--){ this._uploadFile(files[i]); }  
        }
    },
    _uploadFile: function(fileContainer){            
        var id = this._handler.add(fileContainer);
        var name = this._handler.getName(id);        
        this._options.onSubmit(id, name);        
        this._addToList(id, name);
        this._handler.upload(id, this._options.params);        
    },      
    _validateFile: function(file){
        var name,size;
 
        if (file.value){
            // it is a file input            
            // get input value and remove path to normalize
            name = file.value.replace(/.*(\/|\\)/, "");
        } else {
            // fix missing properties in Safari
            name = file.fileName != null ? file.fileName : file.name;
            size = file.fileSize != null ? file.fileSize : file.size;
        }
                    
        if (! this._isAllowedExtension(name)){            
            this._error('typeError',name);
            return false;
            
        } else if (size === 0){            
            this._error('emptyError',name);
            return false;
                                                     
        } else if (size && this._options.sizeLimit && size > this._options.sizeLimit){            
            this._error('sizeError',name);
            return false;            
        }
        
        return true;                
    },
    _addToList: function(id, fileName){
        var item = qq.toElement(this._options.fileTemplate);                
        item.qqFileId = id;

        var fileElement = this._getElement(item, 'file');        
        qq.setText(fileElement, this._formatFileName(fileName));
        this._getElement(item, 'size').style.display = 'none';        

        this._getElement('list').appendChild(item);

        this._filesInProgress++;
    },
    _updateProgress: function(id, loaded, total){
        var item = this._getItemByFileId(id);
        var size = this._getElement(item, 'size');
        size.style.display = 'inline';
        
        var text; 
        if (loaded != total){
            text = Math.round(loaded / total * 100) + '% from ' + this._formatSize(total);
        } else {                                   
            text = this._formatSize(total);
        }          
        
        qq.setText(size, text);
    },
    _formatSize: function(bytes){
        var i = -1;                                    
        do {
            bytes = bytes / 1024;
            i++;  
        } while (bytes > 99);
        
        return Math.max(bytes, 0.1).toFixed(1) + ['kB', 'MB', 'GB', 'TB', 'PB', 'EB'][i];          
    },
    _getItemByFileId: function(id){
        var item = this._getElement('list').firstChild;
        
        // there can't be text nodes in our dynamically created list
        // because of that we can safely use nextSibling
        while (item){            
            if (item.qqFileId == id){
                return item;
            }
            
            item = item.nextSibling;
        }          
    },
    /**
     * delegate click event for cancel link 
     **/
    _bindCancelEvent: function(){
        var self = this,
            list = this._getElement('list');            
        
        qq.attach(list, 'click', function(e){
            e = e || window.event;
            var target = e.target || e.srcElement;

            if (qq.hasClass(target, self._classes.cancel)){
                qq.preventDefault(e);

                var item = target.parentNode;
                self._handler.cancel(item.qqFileId);
                qq.remove(item);
            }
        });

    }    
};

qq.UploadButton = function(o){
    this._options = {
        element: null,  
        // if set to true adds multiple attribute to file input      
        multiple: false,
        // name attribute of file input
        name: 'file',
        onChange: function(input){},
        hoverClass: 'qq-upload-button-hover',
        focusClass: 'qq-upload-button-focus'                       
    };
    
    qq.extend(this._options, o);
        
    this._element = this._options.element;
    
    // make button suitable container for input
    qq.css(this._element, {
        position: 'relative',
        overflow: 'hidden',
        // Make sure browse button is in the right side
        // in Internet Explorer
        direction: 'ltr'
    });   
    
    this._input = this._createInput();
};

qq.UploadButton.prototype = {
    /* returns file input element */    
    getInput: function(){
        return this._input;
    },
    /* cleans/recreates the file input */
    reset: function(){
        if (this._input.parentNode){
            qq.remove(this._input);    
        }                
        
        qq.removeClass(this._element, this._options.focusClass);
        this._input = this._createInput();
    },    
    _createInput: function(){                
        var input = document.createElement("input");
        
        if (this._options.multiple){
            input.setAttribute("multiple", "multiple");
        }
                
        input.setAttribute("type", "file");
        input.setAttribute("name", this._options.name);
        
        qq.css(input, {
            position: 'absolute',
            // in Opera only 'browse' button
            // is clickable and it is located at
            // the right side of the input
            right: 0,
            top: 0,
            zIndex: 1,
            fontSize: '460px',
            margin: 0,
            padding: 0,
            cursor: 'pointer',
            opacity: 0
        });
        
        this._element.appendChild(input);

        var self = this;
        qq.attach(input, 'change', function(){
            self._options.onChange(input);
        });
                
        qq.attach(input, 'mouseover', function(){
            qq.addClass(self._element, self._options.hoverClass);
        });
        qq.attach(input, 'mouseout', function(){
            qq.removeClass(self._element, self._options.hoverClass);
        });
        qq.attach(input, 'focus', function(){
            qq.addClass(self._element, self._options.focusClass);
        });
        qq.attach(input, 'blur', function(){
            qq.removeClass(self._element, self._options.focusClass);
        });

        // IE and Opera, unfortunately have 2 tab stops on file input
        // which is unacceptable in our case, disable keyboard access
        if (window.attachEvent){
            // it is IE or Opera
            input.setAttribute('tabIndex', "-1");
        }

        return input;            
    }        
};

/**
 * Class for uploading files using form and iframe
 */
qq.UploadHandlerForm = function(o){
    this._options = {
        // URL of the server-side upload script,
        // should be on the same domain to get response
        action: '/upload',
        // fires for each file, when iframe finishes loading
        onComplete: function(id, fileName, response){}
    };
    qq.extend(this._options, o);
       
    this._inputs = {};
};
qq.UploadHandlerForm.prototype = {
    /**
     * Adds file input to the queue
     * Returns id to use with upload, cancel
     **/    
    add: function(fileInput){
        fileInput.setAttribute('name', 'qqfile');
        var id = 'qq-upload-handler-iframe' + qq.getUniqueId();       
        
        this._inputs[id] = fileInput;
        
        // remove file input from DOM
        if (fileInput.parentNode){
            qq.remove(fileInput);
        }
                
        return id;
    },
    /**
     * Sends the file identified by id and additional query params to the server
     * @param {Object} params name-value string pairs
     */
    upload: function(id, params){                        
        var input = this._inputs[id];
        
        if (!input){
            throw new Error('file with passed id was not added, or already uploaded or cancelled');
        }                
        
        var fileName = this.getName(id);
                
        var iframe = this._createIframe(id);
        var form = this._createForm(iframe, params);
        form.appendChild(input);

        var self = this;
        this._attachLoadEvent(iframe, function(){            
            self._options.onComplete(id, fileName, self._getIframeContentJSON(iframe));
            
            delete self._inputs[id];
            // timeout added to fix busy state in FF3.6
            setTimeout(function(){
                qq.remove(iframe);
            }, 1);
        });

        form.submit();        
        qq.remove(form);        
        
        return id;
    },
    cancel: function(id){        
        if (id in this._inputs){
            delete this._inputs[id];
        }        

        var iframe = document.getElementById(id);
        if (iframe){
            // to cancel request set src to something else
            // we use src="javascript:false;" because it doesn't
            // trigger ie6 prompt on https
            iframe.setAttribute('src', 'javascript:false;');

            qq.remove(iframe);
        }
    },
    getName: function(id){
        // get input value and remove path to normalize
        return this._inputs[id].value.replace(/.*(\/|\\)/, "");
    },  
    _attachLoadEvent: function(iframe, callback){
        qq.attach(iframe, 'load', function(){
            // when we remove iframe from dom
            // the request stops, but in IE load
            // event fires
            if (!iframe.parentNode){
                return;
            }

            // fixing Opera 10.53
            if (iframe.contentDocument &&
                iframe.contentDocument.body &&
                iframe.contentDocument.body.innerHTML == "false"){
                // In Opera event is fired second time
                // when body.innerHTML changed from false
                // to server response approx. after 1 sec
                // when we upload file with iframe
                return;
            }

            callback();
        });
    },
    /**
     * Returns json object received by iframe from server.
     */
    _getIframeContentJSON: function(iframe){
        // iframe.contentWindow.document - for IE<7
        var doc = iframe.contentDocument ? iframe.contentDocument: iframe.contentWindow.document,
            response;

        try{
            response = eval("(" + doc.body.innerHTML + ")");
        } catch(err){
            response = {};
        }

        return response;
    },
    /**
     * Creates iframe with unique name
     */
    _createIframe: function(id){
        // We can't use following code as the name attribute
        // won't be properly registered in IE6, and new window
        // on form submit will open
        // var iframe = document.createElement('iframe');
        // iframe.setAttribute('name', id);

        var iframe = qq.toElement('<iframe src="javascript:false;" name="' + id + '" />');
        // src="javascript:false;" removes ie6 prompt on https

        iframe.setAttribute('id', id);

        iframe.style.display = 'none';
        document.body.appendChild(iframe);

        return iframe;
    },
    /**
     * Creates form, that will be submitted to iframe
     */
    _createForm: function(iframe, params){
        // We can't use the following code in IE6
        // var form = document.createElement('form');
        // form.setAttribute('method', 'post');
        // form.setAttribute('enctype', 'multipart/form-data');
        // Because in this case file won't be attached to request
        var form = qq.toElement('<form method="post" enctype="multipart/form-data"></form>');

        var queryString = '?';
        for (var key in params){
            queryString += '&' + key + '=' + encodeURIComponent(params[key]);
        }

        form.setAttribute('action', this._options.action + queryString);
        form.setAttribute('target', iframe.name);
        form.style.display = 'none';
        document.body.appendChild(form);

        return form;
    }
};

/**
 * Class for uploading files using xhr
 */
qq.UploadHandlerXhr = function(o){
    this._options = {
        // url of the server-side upload script,
        // should be on the same domain
        action: '/upload',
        onProgress: function(id, fileName, loaded, total){},
        onComplete: function(id, fileName, response){}
    };
    qq.extend(this._options, o);

    this._files = [];
    this._xhrs = [];
};

// static method
qq.UploadHandlerXhr.isSupported = function(){
    return typeof File != "undefined" &&
        typeof (new XMLHttpRequest()).upload != "undefined";    
};

qq.UploadHandlerXhr.prototype = {
    /**
     * Adds file to the queue
     * Returns id to use with upload, cancel
     **/    
    add: function(file){
        return this._files.push(file) - 1;        
    },
    /**
     * Sends the file identified by id and additional query params to the server
     * @param {Object} params name-value string pairs
     */    
    upload: function(id, params){
        var file = this._files[id],
            name = this.getName(id),
            size = this.getSize(id);
        
        if (!file){
            throw new Error('file with passed id was not added, or already uploaded or cancelled');   
        }
                        
        var xhr = this._xhrs[id] = new XMLHttpRequest();
        var self = this;
                                        
        xhr.upload.onprogress = function(e){
            if (e.lengthComputable){
                self._options.onProgress(id, name, e.loaded, e.total);
            }
        };

        xhr.onreadystatechange = function(){
            // the request was aborted/cancelled
            if (!self._files[id]){
                return;
            }
            
            if (xhr.readyState == 4){
                                
                self._options.onProgress(id, name, size, size);
                
                if (xhr.status == 200){
                    var response;
                    
                    try {
                        response = eval("(" + xhr.responseText + ")");
                    } catch(err){
                        response = {};
                    }
                    
                    self._options.onComplete(id, name, response);
                        
                } else {                   
                    self._options.onComplete(id, name, {});
                }
                
                self._files[id] = null;
                self._xhrs[id] = null;                
            }
        };

        // build query string
        var queryString = '&qqfile=' + encodeURIComponent(name);
        for (var key in params){
            queryString += '&' + key + '=' + encodeURIComponent(params[key]);
        }

        xhr.open("POST", this._options.action + queryString, true);
        xhr.send(file);        
    },
    cancel: function(id){
        this._files[id] = null;
        
        if (this._xhrs[id]){
            this._xhrs[id].abort();
            this._xhrs[id] = null;                                   
        }
    },
    getName: function(id){
        // fix missing name in Safari 4
        var file = this._files[id];
        return file.fileName != null ? file.fileName : file.name;       
    },
    getSize: function(id){
        // fix missing size in Safari 4
        var file = this._files[id];
        return file.fileSize != null ? file.fileSize : file.size;
    }
};

//
// Helper functions
//

var qq = qq || {};

//
// Useful generic functions

/**
 * Adds all missing properties from obj2 to obj1
 */
qq.extend = function(obj1, obj2){
    for (var prop in obj2){
        obj1[prop] = obj2[prop];
    }
};

/**
 * @return {Number} unique id
 */
qq.getUniqueId = (function(){
    var id = 0;
    return function(){
        return id++;
    };
})();

//
// Events

qq.attach = function(element, type, fn){
    if (element.addEventListener){
        element.addEventListener(type, fn, false);
    } else if (element.attachEvent){
        element.attachEvent('on' + type, fn);
    }
};
qq.detach = function(element, type, fn){
    if (element.removeEventListener){
        element.removeEventListener(type, fn, false);
    } else if (element.attachEvent){
        element.detachEvent('on' + type, fn);
    }
};

qq.preventDefault = function(e){
    if (e.preventDefault){
        e.preventDefault();
    } else{
        e.returnValue = false;
    }
};
//
// Node manipulations

/**
 * Insert node a before node b.
 */
qq.insertBefore = function(a, b){
    b.parentNode.insertBefore(a, b);
};
qq.remove = function(element){
    element.parentNode.removeChild(element);
};

qq.contains = function(parent, descendant){
    if (parent.contains){
        return parent.contains(descendant);
    } else {
        return !!(descendant.compareDocumentPosition(parent) & 8);
    }
};

/**
 * Creates and returns element from html string
 * Uses innerHTML to create an element
 */
qq.toElement = (function(){
    var div = document.createElement('div');
    return function(html){
        div.innerHTML = html;
        var element = div.firstChild;
        div.removeChild(element);
        return element;
    };
})();

//
// Node properties and attributes

/**
 * Sets styles for an element.
 * Fixes opacity in IE6-8.
 */
qq.css = function(element, styles){
    if (styles.opacity != null){
        if (typeof element.style.opacity != 'string' && typeof(element.filters) != 'undefined'){
            styles.filter = 'alpha(opacity=' + Math.round(100 * styles.opacity) + ')';
        }
    }
    qq.extend(element.style, styles);
};
qq.hasClass = function(element, name){
    var re = new RegExp('(^| )' + name + '( |$)');
    return re.test(element.className);
};
qq.addClass = function(element, name){
    if (!qq.hasClass(element, name)){
        element.className += ' ' + name;
    }
};
qq.removeClass = function(element, name){
    var re = new RegExp('(^| )' + name + '( |$)');
    element.className = element.className.replace(re, ' ').replace(/^\s+|\s+$/g, "");
};
qq.setText = function(element, text){
    element.innerText = text;
    element.textContent = text;
};

//
// Selecting elements

qq.children = function(element){
    var children = [],
    child = element.firstChild;

    while (child){
        if (child.nodeType == 1){
            children.push(child);
        }
        child = child.nextSibling;
    }

    return children;
};

qq.getByClass = function(element, className){
    if (element.querySelectorAll){
        return element.querySelectorAll('.' + className);
    }

    var result = [];
    var candidates = element.getElementsByTagName("*");
    var len = candidates.length;

    for (var i = 0; i < len; i++){
        if (qq.hasClass(candidates[i], className)){
            result.push(candidates[i]);
        }
    }
    return result;
};



/**
 * Objeto agregada, para que despues de subir la imagen, se le aplique un crop mams827
 */ 
var _jCrop = function(options){

    var _id = '#' + options.id + '-image', $ = jQuery, boundx, boundy, jcrop_api,
        ratio = $("#" + options.preview).width() / $("#" + options.preview).height();

    var updatePreview = function(c){
        if (parseInt(c.w) > 0){
            var rx = $("#" + options.preview).width() / c.w;
            var ry = $("#" + options.preview).height() / c.h;
            $("#" + options.preview + " img").css({
                width: Math.round(rx * boundx) + "px",
                height: Math.round(ry * boundy) + "px",
                marginLeft: "-" + Math.round(rx * c.x) + "px",
                marginTop: "-" + Math.round(ry * c.y) + "px"
            });
        }
    };

    var showCoords = function(c){
        $('#' + options.id + '_x').val(c.x);
        $('#' + options.id + '_y').val(c.y);
        $('#' + options.id + '_w').val(c.w);
        $('#' + options.id + '_h').val(c.h);
    };

    $('input[id^=' + options.aspectratio + ']').change(function(){
        jcrop_api.setOptions(this.checked?  { aspectRatio: ratio }: { aspectRatio: 0 });
        jcrop_api.focus();
    });

    var _coord = '';
    _coord += "<input type='hidden' id='" + options.id + "_x' name='" + options.id + "_x' value='' />";
    _coord += "<input type='hidden' id='" + options.id + "_y' name='" + options.id + "_y' value='' />";
    _coord += "<input type='hidden' id='" + options.id + "_w' name='" + options.id + "_w' value='' />";
    _coord += "<input type='hidden' id='" + options.id + "_h' name='" + options.id + "_h' value='' />";


    if(options.upload)
    {
        var _uploader = new qq.FileUploader({
            element: $(_id)[0],
            allowedExtensions: options.extensions,
            action: options.action,
            debug: false,
            labels: options.labels,
            size: options.size,
            onComplete: function(id, file, response){
                var html = "",
                    l = _uploader._handler._files ?  _uploader._handler._files.length : _uploader._handler._inputs.length;

                for(var i=0; i<l; i++)
                    _uploader._handler.cancel(i);

                if(response.error==undefined){
                    $(_id + " div.qq-upload-drop-area").removeClass("qq-upload-drop-area");
                    $(_id + " td").html("<div class='image-loading'></div>");

                    html  = "<input type='hidden' id='" + options.id + "' name='" + options.id + "' value='" + file + "' />";
                    html += _coord;
                    html += "<img src='../tmp/" + file + "' style='display:none;' />";

                    $(_id).append(html);

                    $(_id + " img")[0].onload = function(){
                        var _prev = $("#" + options.preview), wp = _prev.width(), hp = _prev.height();
                        $('div.qq-upload-button').show().click(function(){
                            _prev.html("<table width='100%' height='100%'><tr><td valign='middle'><div class='image-loading'></div></td></tr></table>");
                            var _data = {};
                            _data['rotator']         = $('#rotator').val();
                            _data['image']           = $('#' + options.id).val();
                            _data[options.id + '_x'] = $('#' + options.id + '_x').val();
                            _data[options.id + '_y'] = $('#' + options.id + '_y').val();
                            _data[options.id + '_w'] = $('#' + options.id + '_w').val();
                            _data[options.id + '_h'] = $('#' + options.id + '_h').val();
                            $.ajax({
                                type:    'POST',
                                url:     'index.php?option=com_rotator&controller=banner&task=saveImage&format=raw',
                                data:    _data,
                                dataType: 'json',
                                success: function(response){
                                    if(response.error){
                                        alert(response.error);
                                    }else{
                                        var html  = "<input type='hidden' id='" + options.id + "' name='" + options.id + "' value='" + response.success + "' />",
                                            eratio = $('div.d-ratio').html();
                                        html += _coord;
                                        html += "<img class='ld-banner' src='../tmp/" + response.success + "' width='" + wp + "' height='" + hp + "' />";
                                        $(_id).parents('tr').remove();
                                        $('div.qq-upload-button').remove();
                                        $(document.body).append('<div id="tmp-load" style="display:none">' + html + '<div>');
                                        $('img.ld-banner')[0].onload = function(){
                                            _prev.html($('#tmp-load').html());
                                            $('#tmp-load').remove();
                                            // Creando la imagen del THUMB si existe
                                            if(options.thumb.width!=0 && options.thumb.height!=0){
                                                html  = '<legend>' + _prev.prev().html() + '</legend>';
                                                html += '<table class="banner-thumb" width="100%"><tr>';
                                                html += '<td width="60%">Banner</td>';
                                                html += '<td>Thumb</td></tr><tr>';
                                                html += '<td class="img-banner"></td>';
                                                html += '<td><div id="' + options.preview + '-thumb" class="preview-img" ';
                                                html += 'style="height:' + options.thumb.height + 'px;';
                                                html += 'width:' + options.thumb.width + 'px;';
                                                html += 'overflow:hidden;">';
                                                html += "<img src='../tmp/" + response.success + "' width='";
                                                html += options.thumb.width + "' height='" + options.thumb.height + "' />";
                                                html += '</div></td></tr>';
                                                html += '<tr><td colspan="2"><div class="d-ratio">' + eratio + '</div>';
                                                html += '</td></tr></table>';
                                                var tmp = _prev.clone(true);
                                                _prev.parent().html(html).find('.img-banner').append(tmp);
                                                ratio = options.thumb.width / options.thumb.height;
                                                options.preview += '-thumb';
                                                $("img.ld-banner").Jcrop({
                                                    onChange:   updateJcrop,
                                                    onSelect:   updateJcrop,
                                                    aspectRatio: ratio
                                                }, function(){
                                                    var bounds = this.getBounds();
                                                    jcrop_api = this;
                                                    boundx = bounds[0];
                                                    boundy = bounds[1];
                                                });
                                                $('input[id^=' + options.aspectratio + ']').change(function(){
                                                    jcrop_api.setOptions(this.checked?  { aspectRatio: ratio }: { aspectRatio: 0 });
                                                    jcrop_api.focus();
                                                });
                                            }
                                        }
                                        $('input:disabled, select:disabled, textarea:disabled').attr('disabled', false);
                                        $('body').scrollTop(100);
                                    }
                                }
                            });
                        });
                        $(_id + " div.qq-uploader").remove();
                        $(_id + " div.qq-upload-list").remove();
                        _prev.html("<img src='../tmp/" + file + "' width='" + wp + "' height='" + hp + "' />");

                        var el = $(_id), w = el.outerWidth();

                        el.css("width", w);
                        el.css({"overflow": "auto", "position": "relative", "maxHeight": $(window).height() + "px", "height": "100%"});

                        var updateJcrop = function(c){
                            showCoords(c);
                            updatePreview(c);
                        }
                        $(_id + " img").show().Jcrop({
                            onChange:   updateJcrop,
                            onSelect:   updateJcrop,
                            aspectRatio: ratio
                        }, function(){
                            var bounds = this.getBounds();
                            jcrop_api = this;
                            boundx = bounds[0];
                            boundy = bounds[1];
                            $('input[id^=' + options.aspectratio + ']').attr('disabled', '');
                        });
                    }
                }
            }
        });
    }
    else
    {
        var start = true;
        var updateJcrop = function(c){
            if(start){
                var html = _coord;
                html += '<img src="' + $('div.preview-img > img').attr('src') + '" />'
                $('#' + options.preview).html(html);
                start = false;
            }
            showCoords(c);
            updatePreview(c);
        }

        ratio = options.thumb.width / options.thumb.height;
        $("#" + options.preview + ' img').Jcrop({
            onChange:   updateJcrop,
            onSelect:   updateJcrop,
            aspectRatio: ratio
        }, function(){
            var bounds = this.getBounds();
            options.preview += '-thumb';
            jcrop_api = this;
            boundx = bounds[0];
            boundy = bounds[1];
        });
    }
}


