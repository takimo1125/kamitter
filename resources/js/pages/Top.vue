<template>

    <div>
        <section class="p-home">
            <img :src="'/images/top.jpg'">
        </section>
        <div class="u-bgColor-lightGray">
            <section class="p-container">
                <h2 class="p-container-title"><span class="p-container-span">ABOUT</span></h2>
                <div class="p-container-body">
                    <p>「神ったー」はTwitter自動・効率化ツールです。神ったーの機能は、自動ツイート、自動フォロー、自
                        動アンフォロー、自動いいねの四つの自動機能が使用できます。
                    </p>
                </div>
            </section>
        </div>
        <section class="p-container p-container-ornament " id="menu">
            <div class="p-container p-container-lightGray">
                <h2 class="p-container-title"><span class="p-container-span">MERIT</span></h2>
                <div class="p-container-body">
                    <div class="p-panel-group p-panel-group-flex">
                        <div class="p-panel p-panel-border p-panel-menu">
                            <div class="p-panel-head">
                                <span class="u-ft-corp u-ft-l">作業の自動化</span>
                            </div>
                            <div class="p-panel-foot">
                                <p>フォロー作業や自動ツイート機能などで作業を自動化して、Twitterでの面倒なフォロー作業などを自動化します。</p>
                            </div>
                        </div>

                        <div class="p-panel p-panel-border p-panel-menu">
                            <div class="p-panel-head">
                                <span class="u-ft-corp u-ft-l">作業時間の効率化</span>
                            </div>
                            <div class="p-panel-foot">
                                <p>Twitterでの作業を自動化できますので、作業の時間を削減し、あなたはクリエイティブな作業により集中できます。</p>
                            </div>
                        </div>
                        <div class="p-panel p-panel-border p-panel-menu">
                            <div class="p-panel-head">
                                <span class="u-ft-corp u-ft-l">安心のアカウント連携</span>
                            </div>
                            <div class="p-panel-foot">
                                <p>Twitter 公式の「Twitter ルール」「自動化ルール」に完全準拠した機能のみを提供しています。無許可でのツイート投稿等は一切発生しませんので安心してご利用ください。</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <div class="u-bgColor-lightGray">
            <section class="p-container  u-align-center">
                <vue-typer text="あなたのTwitterに気楽さを！！" :pre-erase-delay='50000' class="u-ft-xl"></vue-typer><br/>
                <RouterLink class="c-button c-button--twitter" to="/login">新規登録/ログイン</RouterLink>
            </section>
        </div>
    </div>
</template>

<script>
import { VueTyper } from 'vue-typer'

export default {
  components: {
    VueTyper
  }
}
</script>
