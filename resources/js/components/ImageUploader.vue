<template>
    <div class="uploader"
         @dragenter="onDragEnter"
         @dragleave="onDragLeave"
         @dragover.prevent
         @drop="onDrop"
         :class="{ dragging: isDragging }">

        <div>
            <i class="fas fa-upload"></i>
            <p>{{ label }}</p>
            <div>ou</div>
            <ValidationProvider tag="div" class="file-input" rules="image" v-slot="{ errors, validate }">
                <label for="file">Choisir un fichier</label>
                <input type="file" id="file" @change="onInputChange" :multiple="multiple">
                <span>{{ errors[0] }}</span>
            </ValidationProvider>
        </div>

        <div class="images-preview" v-if="images.length">
            <div class="img-wrapper"
                 v-for="(image, index) in images"
                 :key="index">
                <img :src="image" :alt="`Image chargée ${index}`">
                <div class="details">
                    <span class="name" v-text="files[index].name"></span>
                    <span class="size" v-text="getFileSize(files[index].size)"></span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            multiple: {
                type: Boolean,
                required: false,
                default: false
            },
            label: {
                type: String,
                required: false,
                default: "Glissez vos images ici"
            },
        },
        data() {
            return {
                isDragging: false,
                dragCount: 0,
                files: [],
                images: []
            }
        },
        methods: {
            onDragEnter(e) {
                e.preventDefault();
                this.dragCount++;
                this.isDragging = true;
            },
            onDragLeave(e) {
                e.preventDefault();
                this.dragCount--;
                if (this.dragCount <= 0) {
                    this.isDragging = false;
                }
            },
            onDrop(e) {
                e.preventDefault();
                e.stopPropagation();
                this.isDragging = false;
                if (!this.multiple) {
                    this.files = [];
                    this.images = [];
                }
                const files = e.dataTransfer.files;
                Array.from(files).forEach(file => this.addImage(file));
            },
            addImage(file) {
                if (!file.type.match('image.*')) {
                    this.$toast.error(`${file.name} is not an image`);
                    return;
                }
                this.files.push(file);

                const img = new Image(),
                    reader = new FileReader();
                reader.onload = (e) => this.images.push(e.target.result);
                reader.readAsDataURL(file);

                // let formData = new FormData();
                // this.files.forEach(file => {
                //     formData.append('images[]', file, file.name);
                //     console.log(file.name);
                // });
                // this.$emit('files', formData);

                this.$emit('files', this.files);
            },
            getFileSize(size) {
                const fSExt = ['Bytes', 'KB', 'MB', 'GB'];
                let i = 0;
                while (size > 900) {
                    size /= 1024;
                    i++;
                }
                return `${(Math.round(size * 100) / 100)} ${fSExt[i]}`;
            },
            onInputChange(e) {
                if (!this.multiple) {
                    this.files = [];
                    this.images = [];
                }
                const files = e.target.files;
                Array.from(files).forEach(file => this.addImage(file));
            },
        }
    }
</script>

<style lang="scss" scoped>
    @import '~@/_variables.scss';

    .uploader {
        width: 100%;
        border: .3rem dashed $primary-color-dark;
        color: $primary-color-dark;
        padding: 2rem;
        text-align: center;
        border-radius: 2rem 1rem 3rem 1rem;
        font-size: 1.2rem;
        font-weight: $bold;
        line-height: 1.6rem;
        text-transform: uppercase;
        position: relative;
        display: block;
        margin: 2rem auto;
        background: white;
        letter-spacing: 0.1em;

        [class^="fa"] {
            display: block;
            margin-bottom: 1rem;
            font-size: 2.5rem;
        }

        .file-input {
            label {
                display: block;
                background: $primary-color-dark;
                color: #fff;
                padding: 1rem;
                border-radius: 5rem;
                margin-top: 1rem;
                cursor: pointer;
            }

            input {
                position: absolute;
                top: 0;
                left: 0;
                opacity: 0;
                z-index: -2;
            }
        }

        .images-preview {
            display: flex;
            flex-flow: row wrap;
            justify-content: center;
            margin-top: 4rem;

            .img-wrapper {
                width: 16rem;
                height: 15rem;
                display: flex;
                flex-flow: column wrap;
                justify-content: space-between;
                align-items: flex-start;
                background-color: #fff;
                box-shadow: 0 0 1rem rgba($primary-color-dark, .4);
                overflow: hidden;
                margin: .5rem;
                border-radius: 1rem;

                img {
                    max-height: 10.5rem;
                    min-width: 100%;
                    object-fit: cover;
                }

                .details {
                    display: flex;
                    flex-flow: column wrap;
                    align-items: self-start;
                    padding: .3rem .6rem;

                    .name {
                        overflow: hidden;
                        height: 1.8rem;
                    }
                }
            }
        }

        &.dragging {
            border: .3rem solid $secondary-color;
            background-color: $secondary-color;
            color: $white;

            .file-input {
                label {
                    background-color: $white;
                    color: $secondary-color;
                }
            }
        }
    }

</style>
