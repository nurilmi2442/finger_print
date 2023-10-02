<template>
	<Dialog header="Please Wait ..." :closable="false" v-model:visible="loading" class="overlay" :breakpoints="{'960px': '75vw', '640px': '100vw'}" :style="{width: '40vw'}">
     <ProgressBar v-if="percent" :value="percent">
     		 Percent Complete: {{percent}}%
     </ProgressBar>
     <ProgressBar v-else mode="indeterminate" style="height: .5em" />
  </Dialog>
</template>
<script>
	import ProgressBar from 'primevue/progressbar';
	import ProgressSpinner from 'primevue/progressspinner';
	export default {
		data(){
			return{
				percentage:80
			}
		},
		components:{
			ProgressBar,
			ProgressSpinner
		},
		props:{
			isUpload:{
				type:Boolean,
				default:false
			},
			percent:{
				type:Number,
				default:0
			},
			loading:{
				type:Boolean,
				default:false
			}
		}
	}
</script>