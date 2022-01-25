<template>
    <div class="h-96 w-full">
        <div class="h-full p2 flex flex-col-reverse overflow-x-auto">
            <div v-for="(message, index) in messages" :key="index">
                <message-item :message="message"/>
            </div>
        </div>
    </div>
</template>
<script>
import MessageItem from './messageItem.vue'
export default {
    props:['messages'],
    components:{
        MessageItem,
    },
}
</script>
