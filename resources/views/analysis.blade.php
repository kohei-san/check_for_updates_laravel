<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('corsin') }}
        </h2>
    </x-slot>

    @foreach ($users as $user)
      {{$user->name}}
      
    @endforeach
    {{$lineCount}}
    {{$activeCallCount}}
    {{$reviewCount}}

    <template>
      <div class="small">
        <line-chart :chart-data="datacollection"></line-chart>
        <button @click="fillData()">Randomize</button>
      </div>
    </template>
    
    <script>
      import LineChart from './LineChart.js'
    
      export default {
        components: {
          LineChart
        },
        data () {
          return {
            datacollection: null
          }
        },
        mounted () {
          this.fillData()
        },
        methods: {
          fillData () {
            this.datacollection = {
              labels: [this.getRandomInt(), this.getRandomInt()],
              datasets: [
                {
                  label: 'Data One',
                  backgroundColor: '#f87979',
                  data: [this.getRandomInt(), this.getRandomInt()]
                }, {
                  label: 'Data One',
                  backgroundColor: '#f87979',
                  data: [this.getRandomInt(), this.getRandomInt()]
                }
              ]
            }
          },
          getRandomInt () {
            return Math.floor(Math.random() * (50 - 5 + 1)) + 5
          }
        }
      }
    </script>
    
    <style>
      .small {
        max-width: 600px;
        margin:  150px auto;
      }
    </style>
    
</x-app-layout>
