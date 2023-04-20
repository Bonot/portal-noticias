<template>
    <select class="form-select"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
    >
        <option value="0" selected>Selecione um autor</option>
        <option v-for="(author, index) in this.authors" :key="index"
            :value="author.id">{{author.name}}</option>
    </select>
</template>

<script>
import axios from 'axios'
import Cookie from 'js-cookie'

export default {
    name: 'authorSelect',
    props: ['modelValue'],
    emits: ['update:modelValue'],
    data(){
        return {
            authors: [],
            token: '',
            config: {}
        }
    },
    mounted(){
        this.token = Cookie.get('token')
        this.config = {
            headers: { 
                Authorization: `Bearer ${this.token}`
            }
        }
        this.getAuthors();
    },
    methods: {
        getAuthors() {
            axios.get(`/api/authors/resource`, this.config)
                .then(response => {
                    this.authors = response.data;
                });
        }
    }
}
</script>
