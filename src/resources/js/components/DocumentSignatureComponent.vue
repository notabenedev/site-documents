<template>
    <div class="row">
        <div class="col-12">
            <div class="card" v-if="signaturesCanCreate">
                <div class="card-body" v-if="signaturesCanCreate">
                    <form :id="'signatureStoreForm' + fileId">
                        <div class="form-group">
                            <div class="custom-file">
                                <input type="file"
                                       name="signature"
                                       :disabled="loading"
                                       class="custom-file-input"
                                       @change.prevent="getSignature"
                                       :id="'signatureFileInput' + fileId">
                                <label class="custom-file-label"
                                       :id="'signatureFileInputLabel' + fileId"
                                       :for="'signatureFileInput' + fileId">
                                  <span v-if="fileContents.length">
                                       <span v-for="(item, index) in fileContents">
                                         {{  item.name }}
                                       </span>
                                  </span>
                                  <span v-else>Выберите файл подписи</span>
                                </label>
                            </div>
                        </div>
                        <div class="btn-group"
                             role="group">
                            <button type="button"
                                    @click="loadSignatures"
                                    :disabled="loading || ! fileContents.length"
                                    class="btn btn-outline-success">
                                <span v-if="loading">Обработка запроса</span>
                                <span v-else>Загрузить</span>
                            </button>
                        </div>
                    </form>
                    <p :class="{ 'text-success': !error, 'text-danger': error}">{{ message }}</p>
                    <p v-for="item in errors" class="text-danger">{{ item }}</p>
                </div>
                <div class="card-footer" v-if="signaturesCanViewAny">
                    <form :id="'signatureUpdateForm' + fileId">
                        <div class="form-group" v-for="(item, index) in fileContents">
                            <div class="input-group mb-3">
                                <input name="title"
                                       type="text"
                                       v-model="item.name"
                                       placeholder="Заголовок"
                                       class="form-control" aria-label="Заголовок">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-danger"
                                            type="button"
                                            @click="fileContents.splice(index, 1)"
                                            id="button-addon2">
                                        <i class="fas fa-minus-circle"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <input name="person" type="text"
                                       v-model="item.person"
                                       placeholder="Подписант"
                                       class="form-control" aria-label="Подписант">
                            </div>
                            <div class="input-group mb-3">
                                <input name="position" type="text"
                                       v-model="item.position"
                                       placeholder="Должность"
                                       class="form-control" aria-label="Должность">
                            </div>
                            <div class="input-group mb-3">
                                <input name="organization" type="text"
                                       v-model="item.organization"
                                       placeholder="Организация"
                                       class="form-control" aria-label="Организация">
                            </div>
                            <div class="input-group mb-3">
                                <input name="date" type="text"
                                       v-model="item.date"
                                       placeholder="Дата и время подписания"
                                       class="form-control" aria-label="Дата и время подписания">
                            </div>
                            <div class="input-group mb-3">
                                <input name="certificate" type="text"
                                       v-model="item.certificate"
                                       placeholder="Сертификат"
                                       class="form-control" aria-label="Сертификат">
                            </div>
                            <div class="input-group mb-3">
                                <textarea name="issued"
                                          v-model="item.issued"
                                          placeholder="Кем выдан"
                                          class="form-control" aria-label="Кем выдан">
                                </textarea>
                            </div>
                            <div class="input-group mb-3">
                                <input name="period" type="text"
                                       v-model="item.period"
                                       placeholder="Период действия сертификата"
                                       class="form-control" aria-label="Период действия сертификата">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12" v-if="files.length">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table" v-if="signaturesCanViewAny">
                            <thead>
                            <tr>
                                <th>Подпись</th>
                                <th>Действия</th>
                            </tr>
                            </thead>
                            <tr v-for="file in files" :key="file.id">
                                <td>
                                    <div v-if="file.titleInput">
                                        <div class="input-group">
                                            <input type="text"
                                                   class="form-control"
                                                   v-model="file.titleChanged">
                                            <div class="input-group-append">
                                                <button class="btn btn-danger"
                                                        @click="file.titleInput = false">
                                                    <i class="fas fa-ban"></i>
                                                </button>
                                                <button class="btn btn-success"
                                                        v-if="signaturesCanUpdate"
                                                        @click="changeName(file)">
                                                    <i class="far fa-check-circle"></i>
                                                </button>
                                            </div>
                                        </div>
                                      <div v-if="signaturesCanView">
                                        <div class="input-group my-2">
                                          <input name="person" type="text" :id="'personChanged' + fileId"
                                                 v-model="file.personChanged"
                                                 placeholder="Подписант"
                                                 class="form-control" aria-label="Подписант">
                                        </div>
                                        <div class="input-group mt-2">
                                          <input type="text" name="position" :id="'positionChanged' + fileId"
                                                 class="form-control"
                                                 placeholder="Должность"
                                                 v-model="file.positionChanged">
                                        </div>
                                        <div class="input-group mt-2">
                                          <input type="text" name="organization" :id="'organizationChanged' + fileId"
                                                 class="form-control"
                                                 placeholder="Организация"
                                                 v-model="file.organizationChanged">
                                        </div>
                                        <div class="input-group mt-2">
                                          <input type="text" name="date" :id="'dateChanged' + fileId"
                                                 class="form-control"
                                                 placeholder="Дата подписания"
                                                 v-model="file.dateChanged">
                                        </div>
                                        <div class="input-group mt-2">
                                          <input type="text" name="certificate" :id="'certificateChanged' + fileId"
                                                 class="form-control"
                                                 placeholder="Сертификат"
                                                 v-model="file.certificateChanged">
                                        </div>
                                        <div class="input-group mt-2">
                                            <textarea name="issued" :id="'issuedChanged' + fileId"
                                                      class="form-control"
                                                      placeholder="Кем выдан"
                                                      v-model="file.issuedChanged">
                                            </textarea>
                                        </div>
                                        <div class="input-group mt-2">
                                          <input type="text" name="period" :id="'periodChanged' + fileId"
                                                 class="form-control"
                                                 placeholder="Период действия сертификата"
                                                 v-model="file.periodChanged">
                                        </div>
                                      </div>
                                    </div>
                                    <button class="btn btn-link" v-else @click="file.titleInput = true">
                                        {{ file.title }}
                                    </button>
                                </td>
                                <td>
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-danger" v-if="signaturesCanDelete"
                                                    :disabled="loading"
                                                    @click="deleteImage(file)">
                                                Удалить
                                            </button>
                                            <a :href="file.downloadUrl" class="btn btn-outline-secondary" target="_blank"
                                               v-if="signaturesCanView">
                                                <i class="fas fa-file-download"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
      <div class="col-12 mt-3" v-else>
        Подписей не добавлено
      </div>
    </div>
</template>

<script>
    export default {
        props: {
            sigUrl: {
                type: String,
                required: true
            },
            showUrl: {
                type: String,
                required: true
            },
            fileId: {
                type: Number,
                required: true
            },
            signaturesCanView:{
              type: Boolean,
              required: false
            },
            signaturesCanViewAny:{
              type: Boolean,
              required: false
            },
            signaturesCanCreate:{
              type: Boolean,
              required: false
            },
            signaturesCanUpdate:{
              type: Boolean,
              required: false
            },
            signaturesCanDelete:{
              type: Boolean,
              required: false
            },
        },
        data() {
            return {
                loading: false,
                fileContents: [],
                message: "",
                errors: [],
                error: false,
                files: [],
            }
        },

        created() {
            axios
                .get(this.showUrl)
                .then(response => {
                    let result = response.data;
                    if (result.success) {
                        this.files = result.files;
                    }
                    else {
                        this.message = result.message;
                    }
                })
        },

        computed: {
        },

        methods: {
            // Восстановить переменные.
            reset() {
                this.loading = false;
                this.error = false;
                this.errors = [];
            },

            // Удаление изображения.
            deleteImage (file) {
                Swal.fire({
                    title: 'Вы уверены?',
                    text: "Сертификат будет невозможно восстановить!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Да, удалить!',
                    cancelButtonText: "Отмена"
                }).then((result) => {
                    if (result.value) {
                        this.loading = true;
                        this.message = "";
                        axios
                            .delete(file.deleteUrl)
                            .then(response => {
                                this.error = false;
                                let result = response.data;
                                if (result.success) {
                                    this.files = result.files;
                                    Swal.fire({
                                        position: 'top-end',
                                        type: 'success',
                                        title: 'Удалено',
                                        showConfirmButton: false,
                                        timer: 1500
                                    });
                                }
                                else {
                                    Swal.fire({
                                        position: 'top-end',
                                        type: 'error',
                                        title: result.message,
                                        showConfirmButton: false,
                                        timer: 1500
                                    });
                                }
                            })
                            .finally(() => {
                                this.reset();
                            });
                    }
                });
            },

            // Меняем имя.
            changeName (file) {
                file.titleInput = false;
                file.personInput = false;
                file.positionInput = false;
                file.organizationInput = false;
                file.dateInput = false;
                file.certificateInput = false;
                file.issuedInput = false;
                file.periodInput = false;
                this.loading = true;
                this.message = "";
                axios
                    .put(file.updateUrl, {
                        title: file.titleChanged,
                        person: file.personChanged,
                        position: file.positionChanged,
                        organization: file.organizationChanged,
                        date: file.dateChanged,
                        certificate: file.certificateChanged,
                        issued: file.issuedChanged,
                        period: file.periodChanged,
                    })
                    .then(response => {
                        let result = response.data;
                        if (result.success) {
                            this.files = result.files;
                            Swal.fire({
                                position: 'top-end',
                                type: 'success',
                                title: 'Подпись изменена',
                                showConfirmButton: false,
                                timer: 1500
                            });
                        }
                        else {
                            Swal.fire({
                                position: 'top-end',
                                type: 'error',
                                title: result.message,
                                showConfirmButton: false,
                                timer: 1500
                            });
                        }
                    })
                    .catch(error => {
                        let data = error.response.data;
                        for (error in data.errors) {
                            if (data.errors.hasOwnProperty(error)) {
                                this.errors.push(data.errors[error][0]);
                            }
                        }
                    })
                    .finally(() => {
                        this.reset();
                    });
            },

            // Получаем выбранное изображение.
            getSignature (event) {
                this.message = '';
                this.fileContents = [];
                for (let item in event.target.files) {
                    if (event.target.files.hasOwnProperty(item)) {
                        this.selectSignature(event.target.files[item]);
                    }
                }
            },

            // Данные по изображению.
            selectSignature (file) {
                let reader = new FileReader();
                reader.onload = (function (inputFile, contents) {
                    return function(event) {
                        let content = event.target.result;
                        let originalName = inputFile.name;
                        let exploded = originalName.split(".");
                        let name = originalName;
                        if (exploded.length > 1) {
                            name = exploded[0];
                        }
                        contents.push({
                            file: inputFile,
                            name: name,
                            content: content
                        })
                    };
                })(file, this.fileContents);
                reader.readAsDataURL(file);
            },

            // Отправить на сервер.
            loadSignatures() {
                this.message = "";
                this.errors = [];
                this.sendSingleSignature();
            },

            // Отправка по одному файлу.
            sendSingleSignature() {
                this.loading = true;
                let formData = new FormData();
                let file = this.fileContents[0].file;
                let name = this.fileContents[0].name;
                let person = this.fileContents[0].person;
                let position = this.fileContents[0].position;
                let organization = this.fileContents[0].organization;
                let date = this.fileContents[0].date;
                let certificate = this.fileContents[0].certificate;
                let issued = this.fileContents[0].issued;
                let period = this.fileContents[0].period;
                formData.append('file', file);
                formData.append("title", name);
                if (person)
                    formData.append("person", person);
                if (position)
                    formData.append("position", position);
                if (organization)
                    formData.append("organization", organization);
                if (date)
                    formData.append("date", date);
                if (certificate)
                    formData.append("certificate", certificate);
                if (issued)
                    formData.append("issued", issued);
                if (period)
                    formData.append("period", period);
                axios
                    .post(this.sigUrl, formData, {
                        responseType: 'json'
                    })
                    .then(response => {
                        let result = response.data;
                        this.reset();
                        if (result.success) {
                            this.files = result.files;
                            this.fileContents.shift();
                            if (this.fileContents.length) {
                                this.loading = true;
                                this.sendSingleFile();
                            }
                        }
                        else {
                            this.errors.push(result.message);
                        }
                    })
                    .catch(error => {
                        let data = error.response.data;
                        this.reset();
                        if (data.errors.image.length) {
                            this.errors.push(data.errors.image[0]);
                        }
                    })
            },
        }
    }
</script>

<style scoped>

</style>