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

export default {
    name: 'authorSelect',
    props: ['modelValue'],
    emits: ['update:modelValue'],
    data(){
        return {
            authors: [],
        }
    },
    mounted(){
        this.getAuthors();
    },
    methods: {
        getAuthors() {
            axios.get(`http://localhost:90/api/authors/resource`)
                .then(response => {
                    this.authors = response.data;
                });
        }
    }
}
</script>
