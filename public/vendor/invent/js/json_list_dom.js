window.json_list_dom = function (json_input_name, selector_dynamic_container, selector_template, selector_form, options = {}) {

    this.btn_edit_select = '.btn-edit';
    this.btn_remove_select = '.btn-remove';
    this.btn_add_select = selector_form + ' .btn-add';
    this.btn_cancel_select = '.btn-cancel';
    this.btn_restore_select = '.btn-restore';
    this.validate = true;
    this.preventRedirect = true;
    let updated = false;
    this.cast = {}
    const self = this;

    const input = {
        element: document.querySelector(`[name="${json_input_name}"]`),
        getValue: function () {
            return (this.element.value == "") ? [] : JSON.parse(decodeURIComponent(this.element.value.replace(/&quot;/g, '"')));
        },
        setValue: function (array) {
            this.element.value = JSON.stringify(this.changeBlankNull(array))
        },
        changeBlankNull: function (array) {
            array = array.map(obj => {
                Object.keys(obj).forEach(key => {
                    if (obj[key] === "") {
                        obj[key] = null;
                    }
                });
                return obj;
            });
            return array;
        },
    }


    this.swap = function (ref, value) {
        if (ref in this.cast) {
            if (typeof this.cast[ref] === "function") {
                return this.cast[ref](value);
            }
            value = value ?? '';
            if (value != "") {
                switch (this.cast[ref]) {
                    case "date":
                        return new Date(value).toLocaleDateString("pt-BR", { timeZone: "UTC" });
                        break;
                    case "select":
                        return form.element.querySelector(`[name='${ref}'] option[value='${value}']`).innerHTML;
                        break;
                    case "select2":
                        return form.element.querySelector(`[name='${ref}'] option[value='${value}']`).innerHTML;
                        break;
                    case "string":
                        return (value == null || value == undefined) ? "" : value;
                        break;
                    case "number":
                        return (value == null || value == undefined) ? "0" : value;
                        break;
                    case "icon":
                        return `<i class="${value}"></i>`;
                        break;
                }
            }
        }
        return value;
    }



    const template = {
        element: document.querySelector(selector_template),
        selector: selector_template,
        template: undefined,
        getTemplate: function () {
            if (typeof (this.template) == "undefined") {
                this.template = this.getCloneOriginalElement();
                this.actionButtons(this.template);
            }
            return this.actionButtons(this.template.cloneNode(true));
        },
        getCloneOriginalElement: function () {
            const clone = this.element.cloneNode(true);
            clone.classList.remove('d-none');
            clone.classList.remove('template');
            clone.classList.add('register');
            return clone;
        },
        createNewElement: function (data) {
            const element = new registro(this.getTemplate());
            element.setData(data);
            return element.element;
        },
        actionButtons: function (element) {
            this.buttonAddAction(element, self.btn_edit_select, self.edit_click);
            this.buttonAddAction(element, self.btn_remove_select, self.remove_click);
            this.buttonAddAction(element, self.btn_restore_select, self.restore_click);
            return element;
        },
        buttonAddAction: function (element, seletor, action) {
            const btn = element.querySelector(seletor);
            if (btn) {
                btn.addEventListener('click', action);
            }
        }



    };

    const registro = function (element, options = {}) {
        this.element = element;
        this.loadButton = function (seletor, hide) {
            let btn = this.element.querySelector(seletor);
            const objct_btn = { element: null, show: function () { }, hide: function () { } };
            if (btn) {
                objct_btn.element = btn;
                objct_btn.show = function () {
                    this.element.classList.remove('d-none');
                }
                objct_btn.hide = function () {
                    this.element.classList.add('d-none');
                }
                if (hide)
                    objct_btn.hide();
            }
            return objct_btn;
        };
        this.initialize = function () {
            const removed = this.element.classList.contains('removed')
            this.btn_edit = this.loadButton(self.btn_edit_select, removed)
            this.btn_remove = this.loadButton(self.btn_remove_select, removed)
            this.btn_restore = this.loadButton(self.btn_restore_select, !removed);
        };


        this.selected = function (selected = true) {
            if (selected) {
                this.element.classList.add('selected');
            } else {
                this.element.classList.remove('selected');
            }
        };

        this.getData = function () {
            const data = {}
            this.element.querySelectorAll('[data-ref]').forEach(campo => {
                const ref = campo.getAttribute('data-ref');
                data[ref] = campo.getAttribute('ref-value');
            });
            return data;
        };

        this.setData = function (data) {
            this.element.querySelectorAll('[data-ref]').forEach(campo => {
                const ref = campo.getAttribute('data-ref');
                campo.setAttribute('ref-value', data[ref] ?? '');
                if (campo instanceof HTMLInputElement ||
                    campo instanceof HTMLTextAreaElement ||
                    campo instanceof HTMLSelectElement) {
                    campo.value = self.swap(ref, data[ref])
                } else {
                    campo.innerHTML = self.swap(ref, data[ref]);
                }
            });

        };

        this.remove = function () {
            this.element.classList.add('removed');
            this.btn_edit.hide();
            this.btn_remove.hide();
            this.btn_restore.show();
            dinamyc_container.save();
            self.remove();
        };

        this.restore = function () {
            this.element.classList.remove('removed');
            this.btn_restore.hide();
            this.btn_edit.show();
            this.btn_remove.show();
            dinamyc_container.save();
            self.restore();
        };

        this.edit = function () {
            dinamyc_container.setSelected(this);
            form.setData(this.getData());
            self.edit();
        }

        Object.keys(options).forEach((index) => {
            this[index] = options[index];
        });

        this.initialize();
    }

    const dinamyc_container = {
        element: document.querySelector(selector_dynamic_container),
        clear: true,
        selected: null,
        addRegister: function (data) {
            if (this.clear) {
                this.element.innerHTML = '';
                this.clear = false;
                this.element.classList.add('indicate-selection', 'indicate-remove');
            }

            if (this.selected) {
                this.selected.setData(form.getData());
                this.flushSelected();
                return;
            }
            this.element.appendChild(template.createNewElement(data))
        },
        flushSelected: function () {
            if (this.selected) {
                this.selected.selected(false);
            }
            this.selected = null;
        },
        setSelected: function (element) {
            if (this.selected) {
                this.selected.selected(false);
            }
            this.selected = element;
            this.selected.selected(true);
        },
        getData: function () {
            const data = [];
            this.element.querySelectorAll('.register:not(.removed)').forEach(item => {
                const reg = new registro(item);
                data.push(reg.getData());
            });
            return data;
        },

        setData: function (data) {
            data.forEach(item => {
                this.addRegister(item);
            });
            this.save();
        },

        save: function () {
            input.setValue(this.getData());
            self.persist();
        }


    };

    const form = {
        element: document.querySelector(selector_form),
        clear: function () {
            this.element.querySelectorAll("[name]").forEach(input => {
                form.set(input.getAttribute('name'), '');
                input.classList.remove('is-invalid');
                input.parentElement.classList.remove('is-invalid');
            });
        },
        get: function (name) {
            let input = this.element.querySelector(`[name='${name}']`);
            if(input){
                if(input.type==='checkbox'){
                    return input.checked ? 1:0;
                }
                return input.value;
            } 
            return null;
            
                
        },
        set: function (name, value) {
            let input = this.element.querySelector(`[name='${name}']`);
            if (input) {
                if(input.type==="checkbox"){
                    input.checked =  value==="1";
                }
                input.value = (value == undefined || value == null || value == 'null') ? "" : value;
                if (self.cast[name] === 'select2') {
                    $(input).trigger('change');
                }
            }
        },
        setData: function (data) {
            Object.keys(data).forEach((name) => {
                form.set(name, data[name]);
            });
        },
        getData: function () {
            const data = {};
            this.element.querySelectorAll("[name]").forEach(input => {
                data[input.name] = form.get(input.name)
            });
            return data;
        },
        validity: function () {
            let valid = true;
            this.element.querySelectorAll("[name]").forEach(input => {
                if (input.checkValidity() == false) {
                    valid = false;
                    input.classList.add('is-invalid');
                    input.parentElement.classList.add('is-invalid');
                }
            });
            return valid;
        }
    };

    let abort = false;

    if (input.element == null) {
        console.log("O input com a lista origina não foi encontrado");
        abort = true;
    }
    if (template.element == null) {
        console.log("O template para a exibição dos dados não foi econtrado");
        abort = true;
    }
    if (dinamyc_container.element == null) {
        console.log("O componente para exibição dos dados não foi encontrado");
        abort = true;
    }
    if (form.element == null) {
        console.log("O formulario para a adição de novos dados não foi encontrado");
        abort = true;
    }

    if (abort)
        return;




    this.edit = function () { }

    this.restore = function () { }

    this.cancel = function () { }

    this.persist = function () { }

    this.add = function () { }

    this.remove = function () { }

    this.validity = function () {
        return true;
    }

    this.edit_click = function (event) {
        const elemento = new registro(event.target.closest('.register'));
        elemento.edit();
    }

    this.remove_click = function (event) {
        const elemento = new registro(event.target.closest('.register'));
        elemento.remove();
        updated = true;
    }

    this.restore_click = function (event) {
        const elemento = new registro(event.target.closest('.register'));
        elemento.restore();
        updated = true;
    }

    this.cancel_click = function (event) {
        dinamyc_container.flushSelected();
        form.clear();
        self.cancel();
    }

    this.add_click = function (event) {
        if (self.validate && (!form.validity() || !self.validity())) {
            return false;
        }
        dinamyc_container.addRegister(form.getData());
        dinamyc_container.save();
        updated = true;
        form.clear()
        self.add();
    }

    this.save_click = function () {
        dinamyc_container.save();
    }


    const btn_add = document.querySelector(this.btn_add_select);
    if (btn_add) {
        btn_add.addEventListener('click', this.add_click);
    }


    Object.keys(options).forEach((index) => {
        self[index] = options[index];
    });

    dinamyc_container.setData(input.getValue());

    window.addEventListener('beforeunload', function (event) {
        if (updated) {
            event.preventDefault(); // Requerido por alguns navegadores
            event.returnValue = ''; // Isso ativa o diálogo de confirmação
        }
    });
    if (input.element.form) {
        input.element.form.addEventListener('submit', function (event) {
            updated = false;
        });

    }

};

