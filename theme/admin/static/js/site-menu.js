$(function(){
    
    window.SM = {
        el: {
            model  : $('#field-links'),
            editor : $('#links-editor'),
            edit   : {
                save  : $('#edit-save'),
                link  : $('#edit-link'),
                label : $('#edit-label')
            }
        },
  
        editID: null,
  
        links: [],
        
        _init: function(){
            if(SM.el.model.val())
                SM.links = JSON.parse(SM.el.model.val());
            
            SM.el.edit.label.keydown(function(e){
                if(e.keyCode == 13){
                    e.preventDefault();
                    SM.el.edit.link.focus();
                    return false;
                }
            });
            
            SM.el.edit.link.keydown(function(e){
                if(e.keyCode == 13){
                    e.preventDefault();
                    SM.el.edit.save.focus();
                    return false;
                }
            });
            
            SM.render(SM.links, SM.el.editor);
            
            SM.el.editor
            .nestable({
                collapseBtnHTML: '<i></i>',
                expandBtnHTML: '<i></i>',
                maxDepth: 100
            })
            .on('change', function(){
                SM.links = $(this).nestable('serialize');
                SM.el.model.val(JSON.stringify(SM.links));
            });
            
            SM.el.edit.save.click(SM.save);
        },
  
        edit: function(id, links){
            SM.editID = id;
            
            for(var i=0; i<links.length; i++){
                var link = links[i];
                if(link.id == id){
                    SM.el.edit.label.val(link.label);
                    SM.el.edit.link.val(link.link);
                    SM.el.edit.label.focus();
                    break;
                }
                
                if(link.children && link.children.length)
                    SM.edit(id, link.children);
            }
        },
        
        remove: function(id, links){
            var newLinks = [];
            
            for(var i=0; i<links.length; i++){
                var link = links[i];
                if(link.id == id)
                    continue;
                
                if(link.children){
                    link.children = SM.remove(id, link.children);
                    if(!link.children.length)
                        delete link.children;
                }
                
                newLinks.push(link);
            }
            
            return newLinks;
        },
  
        render: function(links, cont){
            var ol = $('<ol class="dd-list"></ol>');
            cont.append(ol);
            
            for(var i=0; i<links.length; i++){
                var link = links[i];
                
                var li = $('<li class="dd-item"></li>');
                li.attr('id', 'item-' + link.id);
                ol.append(li);
                
                for(var k in link)
                    (k == 'children' || li.data(k, link[k]));
                
                var btns = $('<div class="btn-group btn-group-xs pull-right"></div>');
                li.append(btns);
                
                var bEdit = $('<button type="button" class="btn btn-default" title="Edit"></button>');
                btns.append(bEdit);
                bEdit.append('<i class="fa fa-pencil" aria-hidden="true"></i>');
                bEdit.data('id', link.id);
                bEdit.click(function(){
                    var id = $(this).data('id');
                    $('#item-'+id).addClass('editing');
                    SM.edit(id, SM.links);
                });
                
                var bDel = $('<button type="button" class="btn btn-default" title="Delete"></button>');
                btns.append(bDel);
                bDel.append('<i class="fa fa-trash-o" aria-hidden="true"></i>');
                bDel.data('id', link.id);
                bDel.click(function(){
                    SM.links = SM.remove($(this).data('id'), SM.links);
                    SM.update();
                });
                
                var ddHandler = $('<div class="dd-handle"></div>');
                li.append(ddHandler);
                
                if(link.label){
                    var h4 = $('<h4></h4>');
                    ddHandler.append(h4);
                    h4.text(link.label);
                    
                    if(link.link){
                        var small = $('<small></small>');
                        small.text(link.link);
                        h4.append(small);
                    }
                }
                
                if(link.children)
                    SM.render(link.children, li);
            }
        },
        
        save: function(){
            if(!SM.editID){
                SM.links.push({
                    id    : (new Date()).getTime(),
                    label : SM.el.edit.label.val(),
                    link  : SM.el.edit.link.val()
                });
            }else{
                SM.links = SM.saveRecursive(SM.links);
            }
            
            SM.el.edit.label.val('');
            SM.el.edit.link.val('');
            SM.el.edit.label.focus();
            SM.editID = null;
            
            SM.update();
        },
        
        saveRecursive: function(links){
            var newLinks = [];
            for(var i=0; i<links.length; i++){
                var link = links[i];
                if(link.id == SM.editID){
                    link.link  = SM.el.edit.link.val();
                    link.label = SM.el.edit.label.val();
                }
                if(link.children && link.children.length)
                    link.children = SM.saveRecursive(link.children);
                newLinks.push(link);
            }
            
            return newLinks;
        },
  
        update: function(){
            SM.el.editor.html('');
            SM.render(SM.links, SM.el.editor);
            SM.el.editor.change();
        }
    };
    
    
    
    
    if(SM.el.model.get(0))
        SM._init();
});