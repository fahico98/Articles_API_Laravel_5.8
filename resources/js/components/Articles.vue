
<template>
   <div class="my-4">

      <h2 class="mb-3">Articles</h2>

      <!-- Button trigger new article modal form -->
      <button type="button" v-on:click="clearModalForm" class="btn btn-outline-primary" data-toggle="modal"
         data-target="#articleModalForm">
         Add article
      </button>

      <!-- New article modal form -->
      <div class="modal fade" id="articleModalForm" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
         <div class="modal-dialog" role="document">
            <div class="modal-content">
               <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">{{ modalTitle }}</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               <form action="#" v-on:submit.prevent="saveArticle()">
                  <div class="modal-body">
                     <div class="form-group">
                        <label for="articleTitleInput">Article title</label>
                        <input type="text" class="form-control" id="articleTitleInput" placeholder="title"
                           v-model="article.title">
                     </div>
                     <div class="form-group">
                        <label for="articleBodyInput">Article body</label>
                        <textarea class="form-control" id="articleBodyInput" placeholder="Article body" rows="4"
                           style="resize: none;" v-model="article.body"></textarea>
                     </div>
                  </div>
                  <div class="modal-footer">
                     <button type="button" class="btn btn-secondary" data-dismiss="modal" ref="cancelButton">
                        Cancel
                     </button>
                     <button type="submit" class="btn btn-primary">{{ saveButtonText }}</button>
                  </div>
               </form>
            </div>
         </div>
      </div>

      <!-- Pagination -->
      <nav class="mt-3">
         <ul class="pagination pagination-sm">
            <li v-bind:class="[{disabled: !pagination.prev_page_url}]" class="page-item">
               <a v-on:click.prevent="fetchArticles(pagination.prev_page_url)" class="page-link" href="#">Previous</a>
            </li>
            <li class="page-item disabled">
               <a class="page-link text-dark" href="#">
                  Page {{ pagination.current_page }} of {{ pagination.last_page }}
               </a>
            </li>
            <li v-bind:class="[{disabled: !pagination.next_page_url}]" class="page-item">
               <a v-on:click.prevent="fetchArticles(pagination.next_page_url)" class="page-link" href="#">Next</a>
            </li>
         </ul>
      </nav>

      <!-- Article card -->
      <div class="card my-3" v-bind:key="article.id" v-for="article in articles">
         <div class="card-body">
            <h5 class="card-title">{{ article.title }}</h5>
            <p class="card-text">{{ article.body }}</p>
            <button v-on:click="showEditModalForm(article)" class="btn btn-outline-primary btn-sm"
               data-toggle="modal" data-target="#articleModalForm">Edit</button>
            <button v-on:click="showDeleteModalForm(article)" class="btn btn-outline-danger btn-sm ml-1">Delete</button>
         </div>
      </div>

      <!-- Button trigger delete article modal form -->
      <button type="button" data-target="#deleteArticleModalForm" data-toggle="modal" style="display: none;"
         ref="deleteTriggerButton">
         delete article
      </button>

      <!-- Delete article modal form -->
      <div class="modal fade" id="deleteArticleModalForm" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalLabel" aria-hidden="true">
         <div class="modal-dialog" role="document">
            <div class="modal-content">
               <div class="modal-header">
               <h5 class="modal-title" id="exampleModalLabel">Delete article</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               <form action="#" v-on:submit.prevent="deleteArticle(article.id)">
                  <div class="modal-body">
                     Are you sure to delete <strong>{{ article.title }}</strong> article ?
                  </div>
                  <div class="modal-footer">
                     <button type="button" class="btn btn-secondary" data-dismiss="modal" ref="cancelDeleteButton">
                        Cancel
                     </button>
                     <button type="submit" class="btn btn-danger">Delete</button>
                  </div>
               </form>
            </div>
         </div>
      </div>

      <!-- Pagination -->
      <nav class="mt-3">
         <ul class="pagination pagination-sm">
            <li v-bind:class="[{disabled: !pagination.prev_page_url}]" class="page-item">
               <a v-on:click.prevent="fetchArticles(pagination.prev_page_url)" class="page-link" href="#">Previous</a>
            </li>
            <li class="page-item disabled">
               <a class="page-link text-dark" href="#">
                  Page {{ pagination.current_page }} of {{ pagination.last_page }}
               </a>
            </li>
            <li v-bind:class="[{disabled: !pagination.next_page_url}]" class="page-item">
               <a v-on:click.prevent="fetchArticles(pagination.next_page_url)" class="page-link" href="#">Next</a>
            </li>
         </ul>
      </nav>

   </div>
</template>

<script>
   export default {
      data: function(){
         return {
            articles: [],
            article: {
               id: "",
               title: "",
               body: ""
            },
            pagination: {},
            edit: false,
            modalTitle: "",
            saveButtonText: ""
         };
      },
      created: function(){
         this.fetchArticles();
      },
      methods: {
         fetchArticles: function(page_url){
            page_url = page_url || "api/articles?page=" + this.pagination.current_page;
            fetch(page_url)
               .then((response) => {return response.json()})
               .then((response) => {
                  this.articles = response.data;
                  this.makePagination(response.meta, response.links);
               }).catch((error) => {console.log(error)});
         },
         makePagination: function(meta, links){
            meta.current_page = (meta.current_page > meta.last_page) ? meta.last_page : meta.current_page;
            this.pagination = {
               current_page: meta.current_page,
               last_page: meta.last_page,
               next_page_url: links.next,
               prev_page_url: links.prev
            };
         },
         deleteArticle: function(id){
            this.$refs.cancelDeleteButton.click();
            fetch("api/article/destroy/" + id, {method: "delete"})
               .then((response) => {return response.json()})
               .then((response) => {
                  if(this.articles.length == 1){
                     this.fetchArticles("api/articles?page=" + (this.pagination.current_page - 1));
                  }else{
                     this.fetchArticles();
                  }
               })
               .catch((error) => {console.log(error)});
         },
         saveArticle: function(){
            this.$refs.cancelButton.click();
            if(!this.edit){
               // Add article...
               fetch("api/article/store", {
                     method: "post",
                     body: JSON.stringify(this.article),
                     headers: {"content-type": "application/json"}
                  })
                  .then((response) => {return response.json()})
                  .then((response) => {
                     this.article.title = "";
                     this.article.body = "";
                     this.fetchArticles();
                  })
                  .catch((error) => {console.log(error)});
            }else{
               // Update article...
               fetch("api/article/update", {
                     method: "put",
                     body: JSON.stringify(this.article),
                     headers: {"content-type": "application/json"}
                  })
                  .then((response) => {return response.json()})
                  .then((response) => {
                     this.article.title = "";
                     this.article.body = "";
                     this.fetchArticles();
                  })
                  .catch((error) => {console.log(error)});
            }
         },
         showEditModalForm: function(article){
            this.edit = true;
            this.modalTitle = "Edit article";
            this.saveButtonText = "Save changes";
            this.article.id = article.id;
            this.article.title = article.title;
            this.article.body = article.body;
         },
         showDeleteModalForm: function(article){
            this.article.id = article.id;
            this.article.title = article.title;
            this.article.body = article.body;
            this.$refs.deleteTriggerButton.click();
         },
         clearModalForm: function(){
            this.edit = false;
            this.modalTitle = "New article";
            this.saveButtonText = "Save";
            this.article.id = "";
            this.article.title = "";
            this.article.body = "";
         }
      }
   }
</script>
