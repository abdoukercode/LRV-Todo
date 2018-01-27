<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">My Tasks</div>

                    <div class="panel-body">
  
                        <div class="input-group">
                            <input 
                                type="text" class="form-control" 
                                v-model="task.name"
                                @keydown.enter="create">
                            <span class="input-group-btn">
                                <button class="btn btn-success" @click="create">New Task</button>
                            </span>
                        </div>  
                        <div class="tasks-list">
                                                  <div class="alert alert-danger" v-if="!tasks.length">
                            You have no tasks
                        </div>
                            <ul class="list-unstyled">
                            
                                <li v-for="task in tasks" :key="task.id" :class="{ done:task.completed }" >
                                    <div class="checkout">
                                        <label>
                                            <input type="checkbox" name="" v-model="task.completed"  @click="done(task)"> {{task.name}}
                                        </label>
                                        <div class="pull-right">
                                            <a href="#" @click.prevent="remove(task)" >x</a>
                                        </div>
                                    </div>
                                </li>
        
                            </ul>
                        </div>
                    </div>
                    <div class="panel-footer" v-if="tasks.length">
                        <span class="label label-default">You have {{tasks.length}} tasks</span>
                        <span class="label label-warning">{{remainingTasks() }} tasks left</span>
                        <span class="label label-success">{{completedTasks()}} tasks done</span>  
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
  mounted() {
    //console.log("hey abdou Component mounted.");
    this.fetchData()
  },
  data (){
      return {
          tasks: [],
          task:{
              name:'',
              completed:false
          }
      }
  },
  methods: {
      fetchData(){
          axios.get('http://localhost:1337/task')
          .then((res)=> {
              this.tasks = res.data
          })
          .catch(err => console.log(err))
      },
      create(){
          axios.post('http://localhost:1337/task', {
              name: this.task.name,
              completed: this.task.completed
          })

          .then((res)=> {
              this.tasks.unshift(res.data)
              //this.task.name = "";
              console.log(this.task.name)
              
          })
          .catch(err => console.log(err))
      },

      done(task){
          axios.put(`http://localhost:1337/task/${task.id}`,{
              completed: !task.completed
          } )
         .then((res)=> {
          
              console.log('Task Updated')
              
          })
          .catch(err => console.log(err))
      },
      remove(task){
          axios.delete(`http://localhost:1337/task/${task.id}`)
         .then((res)=> {
          const taskIndex = this.tasks.indexOf(task)
          this.tasks.splice(taskIndex,1)
              //console.log('Task Updated')
              
          })
          .catch(err => console.log(err))
      },

      remainingTasks(){
          return this.tasks.filter(task=> { return !task.completed}).length

      },

      completedTasks(){
          return this.tasks.filter(task=> { return task.completed}).length  
      }
  }
};
</script>

<style>
.done label {
    text-decoration: line-through;
}
</style>
