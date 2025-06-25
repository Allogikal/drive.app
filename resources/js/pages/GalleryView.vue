<script setup>
import AppLayout from '@/layouts/AppLayoutHeader.vue'

const props = defineProps({
    images: Array
})
</script>

<template>
    <AppLayout>
        <div>
            <div class="container-small title-div">
                <h1 class="title">Взгляни на наш город!</h1>
            </div>
            <section class="gallery container-small">
                <!-- Column 1, Row 1-3: Large Vertical -->
                <div class="card card--span-rows-3">
                    <img :src="images[0]?.src" alt="Placeholder" />
                    <div class="card-comment">
                        <div class="card-comment-img">
                            <div>
                                <img :src="images[0]?.user.src" alt="avatar">
                            </div>
                        </div>
                        <div class="card-comment-content">
                            <h2>{{ props.images[0]?.user?.name }} {{
                                props.images[0]?.user?.surname }}</h2>
                            <p>{{ props.images[0]?.description }}</p>
                        </div>
                    </div>
                </div>

                <!-- Column 2, Row 1: Small Square -->
                <div class="card card--span-rows-2">
                    <img :src="images[1]?.src" alt="Image" />
                    <div class="card-comment">
                        <div class="card-comment-img">
                            <div>
                                <img :src="images[1]?.user.src" alt="avatar">
                            </div>
                        </div>
                        <div class="card-comment-content">
                            <h2>{{ props.images[1]?.user?.name }} {{
                                props.images[1]?.user?.surname }}</h2>
                            <p>{{ props.images[1]?.description }}</p>
                        </div>
                    </div>
                </div>

                <!-- Column 3, Row 1-3: Tall Rectangle -->
                <div class="card card--span-rows-3">
                    <img :src="images[2]?.src" alt="Image" />
                    <div class="card-comment">
                        <div class="card-comment-img">
                            <div>
                                <img :src="images[2]?.user.src" alt="avatar">
                            </div>
                        </div>
                        <div class="card-comment-content">
                            <h2>{{ props.images[2]?.user?.name }} {{
                                props.images[2]?.user?.surname }}</h2>
                            <p>{{ props.images[2]?.description }}</p>
                        </div>
                    </div>
                </div>

                <!-- Column 4, Row 1-3: Very Tall Rectangle -->
                <div class="card card--span-rows-4">
                    <img :src="images[3]?.src" alt="Image" />
                    <div class="card-comment">
                        <div class="card-comment-img">
                            <div>
                                <img :src="images[3]?.user.src" alt="avatar">
                            </div>
                        </div>
                        <div class="card-comment-content">
                            <h2>{{ props.images[3]?.user?.name }} {{
                                props.images[3]?.user?.surname }}</h2>
                            <p>{{ props.images[3]?.description }}</p>
                        </div>
                    </div>
                </div>

                <!-- Column 5, Row 1: Small Square -->
                <div class="card card--span-rows-2">
                    <img :src="images[4]?.src" alt="Image" />
                    <div class="card-comment">
                        <div class="card-comment-img">
                            <div>
                                <img :src="images[4]?.user.src" alt="avatar">
                            </div>
                        </div>
                        <div class="card-comment-content">
                            <h2>{{ props.images[4]?.user?.name }} {{
                                props.images[4]?.user?.surname }}</h2>
                            <p>{{ props.images[4]?.description }}</p>
                        </div>
                    </div>
                </div>

                <!-- Column 6, Row 1: Small Square -->
                <div class="card card--span-rows-2">
                    <img :src="images[5]?.src" alt="Image" />
                    <div class="card-comment">
                        <div class="card-comment-img">
                            <div>
                                <img :src="images[5]?.user.src" alt="avatar">
                            </div>
                        </div>
                        <div class="card-comment-content">
                            <h2>{{ props.images[5]?.user?.name }} {{
                                props.images[5]?.user?.surname }}</h2>
                            <p>{{ props.images[5]?.description }}</p>
                        </div>
                    </div>
                </div>

                <!-- Column 2, Row 2-4: Testimonial Card -->
                <div class="card card--testimonial card--span-rows-4 ">
                    <div class="testimonial__avatar">
                        <img :src="images[6]?.src" alt="Image" />
                        <div class="card-comment">
                            <div class="card-comment-img">
                                <div>
                                    <img :src="images[6]?.user.src" alt="avatar">
                                </div>
                            </div>
                            <div class="card-comment-content">
                                <h2>{{ props.images[6]?.user?.name }} {{
                                    props.images[6]?.user?.surname }}</h2>
                                <p>{{ props.images[6]?.description }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Column 2, Row 4: Large Vertical Below Testimonial -->
                <div class="card card--span-rows-2 card--span-cols-2">
                    <img :src="images[7]?.src" alt="Image" />
                    <div class="card-comment">
                        <div class="card-comment-img">
                            <div>
                                <img :src="images[7]?.user.src" alt="avatar">
                            </div>
                        </div>
                        <div class="card-comment-content">
                            <h2>{{ props.images[7]?.user?.name }} {{
                                props.images[7]?.user?.surname }}</h2>
                            <p>{{ props.images[7]?.description }}</p>
                        </div>
                    </div>
                </div>

                <!-- Column 3, Row 3: Small Square -->
                <div class="card card--span-rows-3">
                    <img :src="images[8]?.src" alt="Image" />
                    <div class="card-comment">
                        <div class="card-comment-img">
                            <div>
                                <img :src="images[8]?.user.src" alt="avatar">
                            </div>
                        </div>
                        <div class="card-comment-content">
                            <h2>{{ props.images[8]?.user?.name }} {{
                                props.images[8]?.user?.surname }}</h2>
                            <p>{{ props.images[8]?.description }}</p>
                        </div>
                    </div>
                </div>

                <!-- Column 4, Row 3-4: Wide Rectangle -->
                <div class="card card--span-rows-3">
                    <img :src="images[9]?.src" alt="Image" />
                    <div class="card-comment">
                        <div class="card-comment-img">
                            <div>
                                <img :src="images[9]?.user.src" alt="avatar">
                            </div>
                        </div>
                        <div class="card-comment-content">
                            <h2>{{ props.images[9]?.user?.name }} {{
                                props.images[9]?.user?.surname }}</h2>
                            <p>{{ props.images[9]?.description }}</p>
                        </div>
                    </div>
                </div>

                <!-- Column 5, Row 2-4: Tall Rectangle -->
                <div class="card card--span-rows-2">
                    <img :src="images[10]?.src" alt="Image" />
                    <div class="card-comment">
                        <div class="card-comment-img">
                            <div>
                                <img :src="images[10]?.user.src" alt="avatar">
                            </div>
                        </div>
                        <div class="card-comment-content">
                            <h2>{{ props.images[10]?.user?.name }} {{
                                props.images[10]?.user?.surname }}</h2>
                            <p>{{ props.images[10]?.description }}</p>
                        </div>
                    </div>
                </div>

                <!-- Column 6, Row 2: Small Square -->
                <div class="card card--span-rows-4">
                    <img :src="images[11]?.src" alt="Image" />
                    <div class="card-comment">
                        <div class="card-comment-img">
                            <div>
                                <img :src="images[11]?.user.src" alt="avatar">
                            </div>
                        </div>
                        <div class="card-comment-content">
                            <h2>{{ props.images[11]?.user?.name }} {{
                                props.images[11]?.user?.surname }}</h2>
                            <p>{{ props.images[11]?.description }}</p>
                        </div>
                    </div>
                </div>

                <!-- Column 3, Row 4-6: Large Vertical Bottom -->
                <div class="card card--span-rows-2 card--span-cols-2">
                    <img :src="images[12]?.src" alt="Image" />
                    <div class="card-comment">
                        <div class="card-comment-img">
                            <div>
                                <img :src="images[12]?.user.src" alt="avatar">
                            </div>
                        </div>
                        <div class="card-comment-content">
                            <h2>{{ props.images[12]?.user?.name }} {{
                                props.images[12]?.user?.surname }}</h2>
                            <p>{{ props.images[12]?.description }}</p>
                        </div>
                    </div>
                </div>

                <!-- Column 4, Row 5: Small Square -->
                <div class="card card--span-rows-2">
                    <img :src="images[13]?.src" alt="Placeholder" />
                    <div class="card-comment">
                        <div class="card-comment-img">
                            <div>
                                <img :src="images[13]?.user.src" alt="avatar">
                            </div>
                        </div>
                        <div class="card-comment-content">
                            <h2>{{ props.images[13]?.user?.name }} {{
                                props.images[13]?.user?.surname }}</h2>
                            <p>{{ props.images[13]?.description }}</p>
                        </div>
                    </div>
                </div>

            </section>
        </div>
    </AppLayout>
</template>