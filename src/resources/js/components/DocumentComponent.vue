<template>
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <form id="documentStoreForm">
            <div class="form-group">
              <div v-if="canCreate" class="custom-file">
                <input type="file"
                       name="document"
                       :disabled="loading"
                       class="custom-file-input"
                       @change.prevent="getFile"
                       multiple
                       id="customFileInput">
                <label class="custom-file-label"
                       for="customFileInput"
                       id="customFileInputLabel">
                  Выберите файл
                </label>
              </div>
            </div>
            <div class="btn-group"
                 role="group">
              <button v-if="canCreate" type="button"
                      @click="loadFiles"
                      :disabled="loading || ! fileContents.length"
                      class="btn btn-outline-success">
                <span v-if="loading">Обработка запроса</span>
                <span v-else>Загрузить</span>
              </button>
              <button class="btn btn-outline-success"
                      type="button"
                      v-if="weightChanged"
                      @click="changeOrder"
                      :class="weightChanged ? 'animated bounceIn' : ''">
                Сохранить порядок
              </button>
            </div>
          </form>
          <p :class="{ 'text-success': !error, 'text-danger': error}">{{ message }}</p>
          <p v-for="item in errors" class="text-danger">{{ item }}</p>
        </div>
        <div class="card-footer">
          <form id="documentUpdateForm" v-if="canUpdate">
            <div class="form-group" v-for="(item, index) in fileContents">
              <div class="input-group mb-3">
                <input type="text"
                       v-model="item.name"
                       placeholder="Имя документа"
                       class="form-control" aria-label="Имя документа">
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
                <textarea name="description" class="form-control"
                          placeholder="Описание документа"
                          aria-label="Описание документа"
                          v-model="item.description">
                </textarea>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <div class="table-responsive">
            <table class="table">
              <thead>
              <tr>
                <th>#</th>
                <th>Документ</th>
                <th>Действия</th>
              </tr>
              </thead>

              <draggable :list="files" group="documents" tag="tbody" handle=".handle" @change="checkMove">
                <tr v-for="file in files" :key="file.id">
                  <th>
                    <i class="fa fa-align-justify handle cursor-move"></i>
                  </th>
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
                                  @click="changeName(file)">
                            <i class="far fa-check-circle"></i>
                          </button>
                        </div>
                      </div>
                      <div class="input-group mt-2">
                        <input name="slug"
                               type="text"
                               class="form-control bg-light"
                               v-model="file.slugChanged">
                      </div>
                      <div class="input-group mt-2">
                        <textarea name="description"
                                  class="form-control"
                                  v-model="file.descriptionChanged">
                        </textarea>
                      </div>
                    </div>
                    <button class="btn btn-link" v-else @click="file.titleInput = true">
                      {{ file.title }}
                    </button>
                  </td>
                  <td>
                    <div v-if="canDelete" class="btn-group" role="group">
                      <button class="btn btn-danger"
                              :disabled="loading"
                              @click="deleteImage(file)">
                        Удалить
                      </button>
                      <a :href="file.downloadUrl" class="btn btn-outline-secondary" target="_blank">
                        <i class="fas fa-file-download"></i>
                      </a>

                      <a class="btn btn-warning" v-if="signaturesCanViewAny" data-toggle="modal" v-bind:data-target="'#documentSigModal' + file.id">
                        <i class="fas fa-signature"></i>
                      </a>
                    </div>

                    <!-- Modal -->
                    <div class="modal fade" v-if="signaturesCanViewAny"
                         v-bind:id="'documentSigModal' + file.id" tabindex="-1"
                         v-bind:aria-labelledby="'documentSigModal' + file.id + 'Label'"
                         aria-hidden="true">
                      <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h4 class="modal-title" v-bind:id="'documentSigModal' + file.id + 'Label'">Подпись .sig</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                            <document-signature-loader :show-url="file.signatureShowUrl" :sig-url="file.signatureStoreUrl" :file-id="file.id"
                            :signatures-can-update="signaturesCanUpdate"
                            :signatures-can-create="signaturesCanCreate"
                            :signatures-can-delete="signaturesCanDelete"
                            :signatures-can-view-any="signaturesCanViewAny"
                            :signatures-can-view="signaturesCanView"
                            >
                            </document-signature-loader>
                          </div>
                        </div>
                      </div>
                    </div>
                  </td>
                </tr>
              </draggable>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import draggable from "vuedraggable";

export default {
  components: {
    draggable,
  },
  props: {
    canDelete:{
      type: Boolean,
      required: false
    },
    canCreate:{
      type: Boolean,
      required: false
    },
    canUpdate:{
      type: Boolean,
      required: false
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
    storeUrl: {
      type: String,
      required: true
    },
    listUrl: {
      type: String,
      required: true
    }
  },
  data() {
    return {
      loading: false,
      fileContents: [],
      message: "",
      errors: [],
      error: false,
      files: [],
      weightChanged: false,
    }
  },

  created() {
    axios
        .get(this.listUrl)
        .then(response => {
          let result = response.data;
          if (result.success) {
            this.files = result.documents;
          }
          else {
            this.message = result.message;
          }
        })
  },

  computed: {
    orderData() {
      let ids = [];
      for (let item in this.files) {
        if (this.files.hasOwnProperty(item)) {
          ids.push(this.files[item].id);
        }
      }
      return ids;
    }
  },

  methods: {
    // Восстановить переменные.
    reset() {
      this.loading = false;
      this.weightChanged = false;
      this.error = false;
      this.errors = [];
    },

    // Показать кнопку если порядок изменен.
    checkMove() {
      this.weightChanged = true;
    },

    // Удаление изображения.
    deleteImage (file) {
      Swal.fire({
        title: 'Вы уверены?',
        text: "Документ будет невозможно восстановить!",
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
      file.descriptionInput = false;
      file.slugInput = false;
      this.loading = true;
      this.message = "";
      axios
          .put(file.updateUrl, {
            title: file.titleChanged,
            slug: file.slugChanged,
            description: file.descriptionChanged,
          })
          .then(response => {
            let result = response.data;
            if (result.success) {
              this.files = result.files;
              Swal.fire({
                position: 'top-end',
                type: 'success',
                title: 'Изменено',
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

    // Сохранить порядок.
    changeOrder() {
      this.loading = true;
      axios
          .put(this.storeUrl, {
            documents: this.orderData,
          })
          .then(response => {
            let result = response.data;
            if (result.success) {
              this.images = result.images;
              Swal.fire({
                position: 'top-end',
                type: 'success',
                title: 'Порядок изменен',
                showConfirmButton: false,
                timer: 1500
              });
            }
            else {
              this.errors.push(result.message);
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
          })
    },

    // Получаем выбранное изображение.
    getFile (event) {
      this.message = '';
      this.fileContents = [];
      for (let item in event.target.files) {
        if (event.target.files.hasOwnProperty(item)) {
          this.selectFile(event.target.files[item]);
        }
      }
    },

    // Данные по изображению.
    selectFile (file) {
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
    loadFiles() {
      this.message = "";
      this.errors = [];
      this.sendSingleFile();
    },

    // Отправка по одному файлу.
    sendSingleFile() {
      this.loading = true;
      let formData = new FormData();
      let file = this.fileContents[0].file;
      let name = this.fileContents[0].name;
      let description = this.fileContents[0].description;
      formData.append('file', file);
      formData.append("title", name);
      if (description)
        formData.append("description", description);
      axios
          .post(this.storeUrl, formData, {
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
