<template>
    <div class="c-panel u-color__bg--white">

        <div class="p-status">
            <p class="p-status__show">{{serviceStatusLabel}}</p>
            <button class="p-status__button c-button c-button--success"
                    v-show="showRunButton"
                    @click.stop="runUnfollowService">サービス開始
            </button>
            <button class="p-status__button c-button c-button--danger"
                    v-show="showStopButton"
                    @click.stop="stopUnfollowService">停止
            </button>
        </div>
        <p class="p-dashboard__notion">※ 自動アンフォロー機能はフォロー数が5000人以内の場合、自動的に停止されます。</p><br />
        <p class="p-dashboard__notion">※ 自動アンフォロー機能でアンフォローするアカウントは、「自動フォローでフォローしてから7日以内にフォロー返ししてくれないユーザー」と「15日ツイートを投稿していないユーザー」の条件に該当するアカウントで、アンフォローリストを作成し、自動でアンフォローします。</p><br />
        <p class="p-dashboard__notion">※ アンフォローリストのアンフォローが全て完了した場合、アンフォロー完了メールが送られます。</p><br />

    </div>
</template>

<script>
    import {OK} from "../utility"
    export default {
        data() {
            return {
                serviceStatus: null,
                serviceStatusLabel: null,
            }
        },
        computed: {
            showRunButton() {
                return this.serviceStatus === 1 || this.serviceStatus === 3
            },
            showStopButton() {
                return this.serviceStatus === 2 || this.serviceStatus === 3
            }
        },
        methods: {
            /**
             * APIを使用して自動アンフォローのステータスを取得する
             */
            async fetchServiceStatus() {
                const response = await axios.get('/api/system/status')
                if (response.status !== OK) {
                    this.$store.commit('error/setCode', response.status)
                    return false
                }
                this.serviceStatus = response.data.auto_unfollow_status
                this.serviceStatusLabel = response.data.status_labels.auto_unfollow
            },
            /**
             * APIを使用して自動アンフォローを実行状態にする
             */
            async runUnfollowService() {
                const serviceType = 2
                const data = {type: serviceType}
                const response = await axios.post('/api/system/run', data)
                if (response.status !== OK) {
                    this.$store.commit('error/setCode', response.status)
                    return false
                }
                this.serviceStatus = response.data.auto_unfollow_status
                this.serviceStatusLabel = response.data.status_labels.auto_unfollow
            },
            /**
             * APIを使用して自動アンフォローを停止状態にする
             */
            async stopUnfollowService() {
                const serviceType = 2
                const data = {type: serviceType}
                const response = await axios.post('/api/system/stop', data)
                if (response.status !== OK) {
                    this.$store.commit('error/setCode', response.status)
                    return false
                }
                this.serviceStatus = response.data.auto_unfollow_status
                this.serviceStatusLabel = response.data.status_labels.auto_unfollow
            }
        },
        created() {
            this.fetchServiceStatus()
        }
    }
</script>
