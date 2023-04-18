<template>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h4>Editar autor</h4>
            </div>
            <div class="card-body">
                <ul class="alert alert-warning" v-if="Object.keys(this.errorList).length > 0">
                    <li class="mb-0 ms-3" v-for="(error, index) in this.errorList" :key="index">
                        {{ error[0] }}
                    </li>
                </ul>
                <div class="mb-3">
                    <label>Nome</label>
                    <input type="text" v-model="model.author.name" class="form-control">
                </div>
            <div class="mb-3">
                <button type="button" @click="updateAuthor" class="btn btn-primary">Salvar</button>
            </div>
        </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios'

export default {
    name: 'authorEdit',
    data(){
        return {
            errorList: '',
            model: {
                author: {
                    name: '',
                }
            }
        }
    },
    mounted(){
        this.authorId = this.$route.params.id;
        this.getAuthorData(this.authorId);
    },
    methods: {
        getAuthorData(authorId) {
            axios.get(`http://localhost:90/api/authors/${authorId}`)
            .then(response => {
                this.model.author = response.data;
            })
            .catch(function (error) {
                console.log(error.response.status)
            })
        },
        updateAuthor() {
            var myThis = this;
            axios.put(`http://localhost:90/api/authors/${this.authorId}`, this.model.author)
                .then(response => {
                    if (response.data.success === false) {
                        myThis.errorList = response.data.errors;
                    }

                    if (response.status === 200) {
                        alert('Autor editado com sucesso');
                        window.location.href = '/autores';
                    }
                })
        }
    }
}
</script>
