<template>
<div id="container">
  <div id="about-container">
    <div id="title">
      What this Blog is About:
    </div>
    <div id="about-content">
      Honest ramblings about life with obsessive compulsive disorder (OCD). 
      I'm a 24 year old Scottish girl who in general is pretty happy, but copes with this chronic mental health disorder on a daily basis.
      Of course some days are more challenging than others and some times have been really tough.
      However, through mindfulness meditation and yoga I have been able to gain some control of my OCD and thoroughly enjoy my life, with it's ups and downs.
      When I first was diagnosed, what helped me the most was reading about other people's experiences and relating so strongly that I no longer felt alone in my issues.
      I hope to do the same for others and to raise awareness about a condition that is very stereotyped and has many misconceptions.
    </div>
  </div>
</div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
<style scoped>
  #container {
    display: flex;
    flex-direction: column;
    align-items: center;
  }
  #about-container {
    max-width: 700px;
    width: 80%;
    padding: 40px 0px ;
    display: flex;
    align-content: center;
    flex-direction: column;
    align-items: center;
  }
  #title {
    font-size: 2em;
    font-weight: bold;
    margin-bottom: 20px;
  }
  #about-content {
    margin: 16px 0;
    line-height: 1.6;
    font-size: 1.2rem;
  }
</style>
