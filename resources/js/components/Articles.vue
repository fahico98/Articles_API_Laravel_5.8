
<template>
   <div class="my-5">
      <h2 class="mb-4">Articles</h2>
      <form action="#" v-on:submit.prevent="saveArticle()">
         <div class="form-group">
            <input type="text" class="form-control" placeholder="Article title" v-model="article.title">
         </div>
         <div class="form-group">
            <textarea class="form-control" placeholder="Article body" rows="4" style="resize: none;"
               v-model="article.body"></textarea>
         </div>
         <button type="submit" class="btn btn-outline-primary">Save article</button>
      </form>
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
      <div class="card my-3" v-bind:key="article.id" v-for="article in articles">
         <div class="card-body">
            <h5 class="card-title">{{ article.title }}</h5>
            <p class="card-text">{{ article.body }}</p>
            <button v-on:click="editArticle(article)" class="btn btn-outline-primary btn-sm">Edit</button>
            <button v-on:click="deleteArticle(article.id)" class="btn btn-outline-danger btn-sm ml-1">Delete</button>
         </div>
      </div>
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
            edit: false
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
            if(confirm("Are you sure to delete this article ?")){
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
            }
         },
         saveArticle: function(){
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
                     this.edit = false;
                     this.fetchArticles();
                  })
                  .catch((error) => {console.log(error)});
            }
         },
         editArticle: function(article){
            this.edit = true;
            this.article.id = article.id;
            this.article.title = article.title;
            this.article.body = article.body;
         }
      }
   }
</script>
