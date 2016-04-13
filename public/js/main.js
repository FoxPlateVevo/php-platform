Upload.MULTI_ITEM = 1;
Upload.MONO_ITEM = 0;

function Upload(settings) {
    //Validate if upload API is supported for the browser
    if (!(window.File && window.FileReader && window.FileList && window.Blob)) {
        console.log("La API controlador de archivos no es sopotado por este buscador...");
        return;
    }

    //PRIVATE VARS
    var u = this,
    defaults = {
        //Params
        id: null,
        name: "defaultName",
        preFiles: [
            {
                name: null, //Name file
                URI: null   //URI Presentation
            }
        ],
        token   : null,
        type    : Upload.MONO_ITEM,
        URI     : "/upload"
    },
    files = [], //nameFiles loaded
    onload = function () {},
    Item = function (settings) { //Class Item
        var i = this,
        defaults = {
            imageURI: false,
            title: null
        };

        i.d = $.extend(true, {}, defaults, settings),
        i.n = {
            item: $("<div>").attr({"class": "item", "title": i.d.title}),
            image: $("<img>").attr({"src": i.d.imageURI}),
            loader: $("<div>").attr({"class": "loader"}),
            close: $("<div>").attr({"class": "btn-close"}).html("<i class='mdi-navigation-close'></i>"),
            progress: $("<div>").attr({"class": "progress"}),
            bar: $("<div>").attr({"class": "bar"})
        };
        
        //Public methods
        i.close = function (callback) {
            i.n.close.click(callback);
        },
        i.ready = function () {
            //Add class to close button for show
            i.n.close.addClass("showable");

            //Hide loader
            i.n.loader.hide();
        };

        //Render the DOM
        i.n.item
        .append(i.n.image)
        .append(i.n.loader)
        .append(i.n.close)
        .append(
            i.n.progress
            .append(i.n.bar)
        );
    },
    Row = function (settings) {
        var r = this,
        defaults = {
            size: null,
            title: null,
            type: Row.TYPE_SUCCESS
        };
        
        r.d = $.extend(true, {}, defaults, settings),
        r.n = {
            row: $("<div>").attr({"class": "row-detail"}).text(r.d.title),
            size: $("<div>").attr({"class": "size"}).text(r.d.size),
            indicator: $("<div>").attr({"class": "indicator"})
        };

        //Render the DOM
        r.n.row
        .append(r.n.size)
        .append(r.n.indicator);

        //Applying settings
        if (r.d.type) {
            r.n.indicator.html("<i class='mdi-navigation-check'></i>");
        } else {
            r.n.row.addClass("error");
            r.n.indicator.html("<i class='mdi-navigation-close'></i>");
        }

        if (!r.d.size) {
            r.n.size.hide();
        }
    },
    bytesToSize = function (bytes) {
        var sizes = ['Bytes', 'KB', 'MB'];

        if (bytes === 0) {
            return 'n/a';
        }
        var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));

        return (bytes / Math.pow(1024, i)).toFixed(1) + ' ' + sizes[i];
    },
    getTypeFile = function (nameFile) {
        //Check img presentation
        var fileExts = ["docx", "doc", "xls", "xlsx", "ppt", "pptx", "mp3", "jpg", "png", "gif"],
                fileExtension = nameFile.split(".").pop(),
                imageURI = null,
                imageName = null,
                fileType = null,
                index = fileExts.indexOf(fileExtension);

        if (index !== -1) {
            switch (index) {
                case 0:
                case 1:
                    imageName = "word_icon.png";
                    fileType = "WORD";
                    break;
                case 2:
                case 3:
                    imageName = "excel_icon.png";
                    fileType = "EXCEL";
                    break;
                case 4:
                case 5:
                    imageName = "ppt_icon.png";
                    fileType = "PPT";
                    break;
                case 6:
                    imageName = "mp3_icon.png";
                    fileType = "MP3";
                    break;
                case 7:
                case 8:
                case 9:
                    imageName = "image_icon.png";
                    fileType = "IMAGE";
                    break;
            }
        } else {
            imageName = "file_icon.png";
            fileType = "OTHER";
        }

        imageURI = "/resources/image/" + imageName;

        return {
            imageURI: imageURI,
            fileType: fileType
        };
    };

    Row.TYPE_ERROR = 0;
    Row.TYPE_SUCCESS = 1;

    //CLASS VARS
    u.d = $.extend(true, {}, defaults, settings),
            u.n = {
                upload: $("<div>").attr({"class": "upload"}),
                content: $("<div>").attr({"class": "upload-content"}),
                item: $("<div>").attr({"class": "item control"}),
                input: $("<input>").attr({"type": "file"}),
                clear: $("<div>").css({"clear": "both"}),
                detail: $("<div>").attr({"class": "upload-detail"}),
                body: $("<div>").attr({"class": "body"}),
                value: $("<input>").attr({"type": "hidden", "name": u.d.name})
            };

    //CLASS METHODS
    u.open = function () {
        if (!u.d.id) {
            console.warn("Don't established a container id");
        } else {
            $(u.d.id).append(u.n.upload);
        }

        return u;
    },
            u.addRowError = function (title, message) {
                var row = new Row({size: message, title: title, type: Row.TYPE_ERROR});

                u.n.body.append(row.n.row);

                setTimeout(function () {
                    row.n.row.fadeOut({
                        complete: function () {
                            row.n.row.remove();
                        }
                    });
                }, 5000);
            },
            u.addRowMessage = function (title, message) {
                var row = new Row({size: message, title: title, type: Row.TYPE_SUCCESS});

                u.n.body.append(row.n.row);

                return row;
            },
            u.addFile = function (nameFile) {
                var imageURI = getTypeFile(nameFile).imageURI;

                //Create Item
                var item = new Item({
                    imageURI: imageURI,
                    title: nameFile
                });

                //Create Row
                var row = u.addRowMessage(nameFile, "En proceso...");

                //Add new item before the input Item
                item.n.item.insertBefore(u.n.item);

                item.close(function () {
                    //Remove DOM item
                    item.n.item.remove();

                    //Remove DOM row
                    row.n.row.remove();

                    //get newFileName
                    var newNameFile = row.d.title;

                    u.deleteNameFile(newNameFile);
                });

                var returnData = {
                    item: item,
                    row: row
                };

                return returnData;
            },
            u.addNameFile = function (nameFile) {
                //Add fileName to files
                files.push(nameFile);

                //Set value FileNames in inputValue
                u.n.value.val(files.join("|"));

                //See if Upload support one item
                if (u.d.type === Upload.MONO_ITEM) {
                    u.n.item.hide();
                }
            },
            u.deleteNameFile = function (nameFile) {
                //Delete fileName from files
                files.splice(files.indexOf(nameFile), 1);

                //Set value FileNames in inputValue
                u.n.value.val(files.join("|"));

                //See if Upload support one item
                if (u.d.type === Upload.MONO_ITEM) {
                    u.n.item.fadeIn();
                }
            },
            u.load = function(callback){
                onload = callback;
            };

    //Render the DOM
    u.n.upload
            .append(
                    u.n.content
                    .append(
                            u.n.item
                            .append(u.n.input)
                            )
                    .append(u.n.clear)
                    )
            .append(
                    u.n.detail
                    .append(u.n.body)
                    .append(u.n.value)
                    );

    //Applying settings
    if (u.d.type === Upload.MULTI_ITEM) {
        u.n.input.attr("multiple", true);
    }

    if (u.d.preFiles.length) {
        u.d.preFiles.forEach(function (prefile) {
            //Evalue if prefile.name is null for default or is empty
            if (prefile.name) {
                //Add file
                var fileData = u.addFile(prefile.name),
                        item = fileData.item,
                        row = fileData.row;

                item.ready();

                //Change message row
                row.n.size.text("El archivo ya ha sido subido con exito");

                //Add nameFile to files
                u.addNameFile(prefile.name);

                //Add src to image if prefile.URI exists
                if (prefile.URI) {
                    item.n.image.attr("src", prefile.URI);
                }
            }
        });
    }

    //Applying events
    u.n.input.on("change", function (e) {
        //Hide Item upload
        if (u.d.type === Upload.MONO_ITEM) {
            u.n.item.fadeOut();
        }

        var input = this;

        $.each(input.files, function (key, file) {
            $.post(u.d.URI, {
                PREPARE_UPLOAD  : true,
                name            : file.name,
                size            : file.size,
                token           : u.d.token
            }, function (data) {
                console.log(data);
                if (data.success) {
                    //Add file
                    var fileData = u.addFile(file.name),
                    item = fileData.item,
                    row = fileData.row;
                    
                    var fileReader = new FileReader();
                    fileReader.readAsDataURL(file);

                    var form = new FormData(),
                    xhr = new XMLHttpRequest();

                    form.append("file", file);
                    form.append("token", u.d.token);
                    
                    xhr.onabort = function (e) {

                    };
                    xhr.onerror = function (e) {

                    };
                    xhr.onload = function (e) {
                        var data = null;
                        
                        try {
                            data = JSON.parse(e.target.responseText);
                            console.log(data);
                        } catch (err) {
                            console.log(e.target.responseText);
                        }

                        if(data){
                            if(data.success){
                                var newNameFile = data.file.name;
                                
                                item.ready();
                                
                                //Change message row
                                row.n.size.text(bytesToSize(file.size));
                                
                                //Add nameFile to files
                                u.addNameFile(newNameFile);
                                
                                //Change title row in defaultData
                                row.d.title = newNameFile;
                                
                                if (data.file.uri) {
                                    item.n.image.attr("src", data.file.uri);
                                }
                                
                                file.newName = newNameFile;
                                
                                onload(file);
                            } else {
                                //Remove DOM item
                                item.n.item.remove();

                                //Remove DOM row
                                row.n.row.remove();

                                //Add error message
                                u.addRowError(file.name, data.message);

                                if (u.d.type === Upload.MONO_ITEM) {
                                    u.n.item.fadeIn();
                                }
                            }
                        } else {
                            //Remove DOM item
                            item.n.item.remove();

                            //Remove DOM row
                            row.n.row.remove();

                            //Add error message
                            u.addRowError(file.name, "Ah ocurrido un error al intentar subir el archivo, por favor vuelva a intentarlo...");

                            if (u.d.type === Upload.MONO_ITEM) {
                                u.n.item.fadeIn();
                            }
                        }
                    };
                    xhr.onloadend = function (e) {

                    };
                    xhr.onloadstart = function (e) {
                        item.n.progress.show();
                    };
                    xhr.upload.onprogress = function (e) {
                        if (e.lengthComputable) {
                            var loaded = e.loaded || e.position,
                                    total = e.total || e.totalSize,
                                    percent = Math.round((loaded / total) * 100),
                                    transfered = bytesToSize(loaded);

                            //Show calculations
                            item.n.bar.css("width", percent + "%");

                            //speed 
                            //remaining

                            if (percent === 100) {
                                item.n.progress.hide();
                            }
                        }
                    };
                    xhr.ontimeout = function (e) {

                    };

                    xhr.open('POST', u.d.URI);
                    xhr.send(form);
                } else {
                    u.addRowError(file.name, data.message);

                    if (u.d.type === Upload.MONO_ITEM) {
                        u.n.item.fadeIn();
                    }
                }
            }, "json")
            .fail(function (xhr) {
                //Fail request
                console.log(xhr.responseText);
            });
        });
    });
}
