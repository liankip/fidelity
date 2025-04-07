<div>
     <a href="{{ route('project.task', $projectIdParam) }}" class="third-color-sne"> <i
               class="fa-solid fa-chevron-left fa-xs"></i> Back</a>
     <h2 class="primary-color-sne mb-5">WBS Chart {{ $projectName }}</h2>
     @if($isTaskSubmitted && !$isTaskApproved && !$isTaskRevision)
          <p class="badge badge-warning">WBS has been submitted, waiting for approval</p>
     @endif

     @if($isTaskApproved)
          <p class="badge badge-success">Task has been approved</p>
     @endif

     @if($isTaskRevision)
          <p class="badge badge-warning">Waiting for revision approval</p>
     @endif
     @if (session('error'))
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
               <p>{{ session('error') }}</p>
               <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
     @endif

     <div>
          <button class="btn btn-primary mb-2 btn-sm" title="Refresh Chart" id="refreshBtn"><i class="fa-solid fa-rotate"></i></button>
          <p class="text-muted" style="font-size: 14px;">*Gunakan tombol refresh ini apabila durasi wbs melebihi timeline chart</p>
     </div>

     <div class="form-group w-25">
          <label for="scaleSelect" class="form-label">Select Scale</label>
          <select class="form-select" id="scaleSelect">
              <option value="day">Daily</option>
              <option value="week">Weekly</option>
              <option value="month">Monthly</option>
              <option value="year">Yearly</option>
          </select>
      </div>
      
     <div id="gantt-chart" style="width:100%; min-height: 70vh; max-height: 90vh" wire:ignore></div>

     <div class="d-flex align-items-center mt-3">
          <button class="btn btn-info" title="Download PDF" type="button" id="exportPDFbtn">
               <i class="fa-solid fa-file-pdf"></i>
          </button>

          <div class="d-flex justify-content-between w-100">
               @if (!$isTaskApproved && !$readOnly && !$isTaskRevision && !$isTaskSubmitted)
                    <button class="btn btn-primary" wire:click="getCriticalData">Calculate Critical Path</button>
                    <button class="btn btn-success" wire:click="saveChart">Submit Chart</button>
               @endif
          </div>
     </div>

     @if ($publicCritical !== null && count($publicCritical) > 0)
          <div class="mt-5">
               <h3>Critical WBS</h3>

               <table class="table table-light table-bordered">
                    <thead>
                         <tr>
                              <th>No</th>
                              <th>WBS Name</th>
                              <th>Project Weight</th>
                              <th>Cost Weight</th>
                              <th>Slack</th>
                              <th>Duration</th>
                              <th>Path</th>
                         </tr>
                    </thead>

                    <tbody>
                         @php
                              $iteration = 0;
                              $totalBobot = 0;
                              $totalBobotCost = 0;
                         @endphp
                         @foreach ($publicCritical as $sectionGroup => $tasks)
                              @php
                                   $sectionName = '';

                                   if ($sectionGroup !== '' && $sectionGroup !== null) {
                                       $sectionName =
                                           \App\Models\Task::where('id', intval($sectionGroup))->first()->task ?? $sectionGroup;
                                   }

                              @endphp
                              <tr>
                                   <td colspan="7"><strong>{{ $sectionName }}</strong></td>
                              </tr>

                              @foreach ($tasks as $task)
                                   @php
                                        $iteration++;
                                        $totalBobot += $task['bobot'];
                                        $totalBobotCost += $task['bobot_cost'];
                                   @endphp
                                   <tr>
                                        <td>{{ $iteration }}</td>
                                        <td>{{ $task['task'] }}</td>
                                        <td>{{ $task['bobot'] }}</td>
                                        <td>{{ number_format($task['bobot_cost']) }}</td>
                                        <td>{{ $task['slack'] ?? '-' }}</td>
                                        <td>{{ $task['duration'] }}</td>
                                        <td>
                                             @if ($task['slack'] == 0.0 && $task['slack'] !== null)
                                                  <small class="badge badge-danger">Critical</small>
                                             @else
                                                  <small class="badge badge-success">Non Critical</small>
                                             @endif
                                        </td>
                                   </tr>
                              @endforeach

                              @if ($loop->last)
                                   <tr>
                                        <td colspan="2"><strong>Total</strong></td>
                                        <td><strong>{{ $totalBobot }}</strong></td>
                                        <td><strong>{{ number_format($totalBobotCost) }}</strong></td>
                                        <td colspan="3"></td>
                                   </tr>
                              @endif
                         @endforeach
                    </tbody>
               </table>
               <button class="btn btn-success mt-2 mb-3" title="Export to Excel" type="button" wire:click="criticalTaskExport">
                    <i class="fa-solid fa-file-excel"></i>
               </button>
          </div>
     @endif

     <script>
          document.addEventListener("DOMContentLoaded", () => {
               gantt.plugins({
                    export_api: true
               });

               function exportPdf(){
                    gantt.exportToPDF({
                         name : "Task Chart {{ $projectName }}.pdf",
                         additional_settings : {
                              landscape: true
                         }
                    })
               }

               $('#exportPDFbtn').on('click', exportPdf);


               if (@js($isTaskApproved) || @js($readOnly) || @js($isTaskSubmitted) || @js($isTaskRevision)) {
                    gantt.config.readonly = true;
               }

               gantt.config.order_branch = true;

               const editors = {
                    predecessors: {
                         type: "predecessor",
                         map_to: "auto"
                    },
               };

               gantt.config.columns = [{
                         name: "wbs",
                         label: "#",
                         width: 60,
                         align: "center",
                         template: gantt.getWBSCode
                    },
                    {
                         name: "text",
                         label: "WBS name",
                         tree: true,
                         width: 200,
                    },
                    // {
                    //      name: "start_date",
                    //      label: "Start time",
                    //      width: 150,
                    //      align: "center",
                    //      template: function(task) {
                    //           return gantt.date.date_to_str("%d %M %Y")(task.start_date);
                    //      }
                    // },
                    {
                         name: "duration",
                         label: "Duration",
                         width: 80,
                         align: "center"
                    },
                    {
                         name: "predecessors",
                         label: "Predecessors",
                         width: 90,
                         align: "center",
                         editor: editors.predecessors,
                         resize: true,
                         template: function(task) {
                              const links = task.$target;
                              const labels = [];
                              for (let i = 0; i < links.length; i++) {
                                   const link = gantt.getLink(links[i]);
                                   const pred = gantt.getTask(link.source);
                                   labels.push(gantt.getWBSCode(pred));
                              }
                              return labels.join(", ")
                         }
                    },
                    {
                         name: "add",
                         label: "",
                         width: 44
                    }
               ];

               gantt.config.min_grid_column_width = 100;
               gantt.config.grid_elastic_columns = true;

               const dropdownTypes = [{
                         key: "task",
                         label: "Task"
                    },
                    {
                         key: "project",
                         label: "Section"
                    }
               ];


               gantt.form_blocks["project_weight"] = {
                    render: function(sns) {
                         return `<div class='dhx_cal_ltext' style='height:50px;'>
                    <input class='editor_bobot' type='number' step='0.001' min='0' style='width: 100%;'>
                </div>`;
                    },
                    set_value: function(node, value, task) {
                         node.querySelector(".editor_bobot").value = task.project_weight || 0;
                    },
                    get_value: function(node, task) {
                         task.project_weight = parseFloat(node.querySelector(".editor_bobot").value) ||
                              0;
                         return task.project_weight;
                    },
                    focus: function(node) {
                         let input = node.querySelector(".editor_bobot");
                         input.focus();
                         input.select();
                    }
               };

               gantt.form_blocks["cost_weight"] = {
                    render: function(sns) {
                         return `<div class='dhx_cal_ltext' style='height:50px;'>
                    <input class='editor_cost' type='number' step='0.01' min='0' style='width: 100%;'>
                </div>`;
                    },
                    set_value: function(node, value, task) {
                         node.querySelector(".editor_cost").value = task.cost_weight || 0;
                    },
                    get_value: function(node, task) {
                         task.cost_weight = parseFloat(node.querySelector(".editor_cost").value) || 0;
                         return task.cost_weight;
                    },
                    focus: function(node) {
                         let input = node.querySelector(".editor_cost");
                         input.focus();
                         input.select();
                    }
               }

               gantt.form_blocks["consumables"] = {
                    render: function(sns) {
                         return `<div class='dhx_cal_ltext' style='display: flex; align-items: center; gap: 10px; margin-bottom: 5px;'>
                         <input class='editor_consumables' type='checkbox'>
                         <span class='dhx_cal_ltext'>Consumables</span>
                         </div>`;
                         
                    },
                    set_value: function(node, value, task) {
                         node.querySelector(".editor_consumables").checked = task.consumables || false;
                    },
                    get_value: function(node, task) {
                         task.consumables = node.querySelector(".editor_consumables").checked;
                         return task.consumables;
                    },
                    focus: function(node) {
                         let input = node.querySelector(".editor_consumables");
                         input.focus();
                         input.select();
                    }
               }

               gantt.config.lightbox.sections = [{
                         name: "description",
                         height: 50,
                         map_to: "text",
                         type: "textarea",
                         focus: true
                    },
                    {
                         name: "project_weight",
                         height: 50,
                         map_to: "project_weight",
                         type: "project_weight"
                    },
                    {
                         name: "cost_weight",
                         height: 50,
                         map_to: "cost_weight",
                         type: "cost_weight"
                    },
                    {
                         name: "type",
                         height: 50,
                         type: "select",
                         options: dropdownTypes,
                         map_to: "type"
                    },
                    {
                         name: "consumables",
                         height: 50,
                         map_to: "consumables",
                         type: "consumables"
                    },
                    {
                         name: "time",
                         height: 72,
                         type: "duration",
                         map_to: "auto"
                    }
               ];

               gantt.locale.labels.section_description = "Task Name";
               gantt.locale.labels.section_project_weight = "Project Weight";
               gantt.locale.labels.section_cost_weight = "Cost Weight";
               gantt.locale.labels.section_consumables = "";


               function setScaleConfig(level) {
                    gantt.config.min_column_width = 100;
                    switch (level) {
                         case "day":
                              gantt.config.scales = [
                                   {unit: "day", step: 1, format: "%d %M"}
                              ];
                              gantt.config.scale_height = 27;
                              break;
                         case "week":
                              var weekScaleTemplate = function (date) {
                              var dateToStr = gantt.date.date_to_str("%d %M");
                              var endDate = gantt.date.add(gantt.date.add(date, 1, "week"), -1, "day");
                              return dateToStr(date) + " - " + dateToStr(endDate);
                              };
                              gantt.config.scales = [
                                   {unit: "week", step: 1, format: weekScaleTemplate},
                                   {unit: "day", step: 5, format: "%D"}
                              ];
                              gantt.config.scale_height = 50;
                              break;
                         case "month":
                              gantt.config.scales = [
                                   {unit: "month", step: 1, format: "%F, %Y"},
                                   {unit: "day", step: 6, format: "%j, %D"}
                              ];
                              gantt.config.scale_height = 50;
                              break;
                         case "year":
                              gantt.config.scales = [
                                   {unit: "year", step: 1, format: "%Y"},
                                   {unit: "month", step: 1, format: "%M"}
                              ];
                              gantt.config.scale_height = 90;
                              break;
                    }
               }

               gantt.init("gantt-chart");

               

               const projectId = @js($projectIdParam);
               const apiUrl = `/api/task/${projectId}`;
               gantt.load(apiUrl);



               gantt.attachEvent("onBeforeTaskDisplay", function(id) {
                    if (id == null) return false;
                    let task = gantt.getTask(id);

                    let parentType = ''

                    if (task.$rendered_parent != "0" && task.$rendered_parent !== null && task
                         .$rendered_parent != 0 &&
                         task.$rendered_parent != undefined) {
                         parentType = gantt.getTask(task.$rendered_parent).type
                    }


                    if (task.$level >= 1 && parentType == "task") {
                         task.hide_bar = true;
                    }

                    return true;
               });


               var dp = new gantt.dataProcessor('/api');
               dp.init(gantt);
               dp.setTransactionMode("REST");


               gantt.attachEvent("onBeforeLinkAdd", function(id, link) {
                    var sourceTask = gantt.getTask(link.source);
                    var targetTask = gantt.getTask(link.target);

                    // Prevent linking to or from tasks of type 'project'
                    if (sourceTask.type === 'project' || targetTask.type === 'project') {
                         return false;
                    }

                    return true;
               });

               gantt.attachEvent("onLightboxSave", function(id, task, is_new) {
                    task.projectParam = @json($projectIdParam);

                    return true;
               });


               function refreshTaskData(){
                    gantt.eachTask(function(task) {
                         task.initial_start_date = task.start_date;
                         task.initial_end_date = task.end_date;

                         const successors = gantt.getLinks().filter(link => link.source == task
                              .id);

                         if (successors.length > 0) {
                              successors.forEach(link => {
                                   let successor = gantt.getTask(link.target);

                                   // Find all predecessors of the successor
                                   const predecessors = gantt.getLinks().filter(
                                        link => link.target == successor.id);

                                   if (predecessors.length > 1) {
                                        // If there are multiple predecessors, find the one with the latest end date
                                        let latestPredecessor = null;
                                        let latestEndDate = null;


                                        predecessors.forEach(link => {
                                             let predecessor = gantt
                                                  .getTask(link.source);

                                             // Compare to find the predecessor with the latest end date
                                             if (!latestEndDate || predecessor.end_date > latestEndDate || (predecessor.end_date === latestEndDate && predecessor.id > latestPredecessor.id)) {
                                                  latestPredecessor = predecessor;
                                                  latestEndDate = predecessor.end_date;
                                             }
                                        });

                                        // Now `latestPredecessor` is the task with the latest end date
                                        if (latestPredecessor) {
                                             // Calculate the offset based on the latest predecessor's end date
                                             let diffInMs = successor.start_date
                                                  .getTime() - latestPredecessor
                                                  .end_date.getTime();
                                             let diffInDays = diffInMs / (1000 * 60 *
                                                  60 * 24);

                                             successor._start_offset = diffInDays;
                                             successor._latest_predecessor =
                                                  latestPredecessor;
                                        }
                                   } else if (predecessors.length === 1) {
                                        // Single predecessor logic (already handled in your code)
                                        let predecessor = gantt.getTask(predecessors[
                                             0].source);

                                        let diffInMs = successor.start_date
                                             .getTime() - predecessor.end_date
                                             .getTime();
                                        let diffInDays = diffInMs / (1000 * 60 * 60 *
                                             24);

                                        successor._start_offset = diffInDays;
                                        successor._latest_predecessor = predecessor;
                                   }
                              });
                         }
                    });
               }

               gantt.attachEvent("onLoadEnd", function(url, type) {
                    refreshTaskData();
               });


               gantt.attachEvent("onBeforeTaskUpdate", function(id, task) {
                    let updatedTasks = new Set(); // Use a Set to avoid duplicate tasks
                    const successors = gantt.getLinks().filter(link => link.source == task.id);

                    if (successors.length > 0) {
                         successors.forEach(link => {
                              let successor = gantt.getTask(link.target);

                              // Ensure successor exists
                              if (!successor) {
                                   console.warn("Successor task not found for link", link);
                                   return; // Skip if successor does not exist
                              }

                              // Ensure the offset is defined for the successor
                              if (typeof successor._start_offset === "undefined") {
                                   console.warn("Offset missing for successor:", successor.id);
                                   return; // Skip if offset is not defined
                              }

                              const diffInDays = successor._start_offset;

                              // If current task is not the latest predecessor, skip processing
                              if (successor._latest_predecessor !== task) {
                                   return;
                              }

                              let newStartDate;

                              // Update the successor's start_date and end_date
                              if (diffInDays == 0) {
                                   newStartDate = new Date(task.end_date.getTime());
                              }

                              if (diffInDays < 0) {
                                   const taskInitialStartDate = new Date(task.initial_start_date);
                                   const successorStartDate = new Date(successor.start_date);
                                   const isSameDate = taskInitialStartDate.toDateString() ===successorStartDate.toDateString();

                                   if (isSameDate) {
                                        newStartDate = task.start_date;
                                   }
                              }

                              if (diffInDays > 0) {
                                   newStartDate = new Date(task.end_date.getTime() + (
                                        diffInDays * 24 * 60 * 60 * 1000));
                              }

                              // Make sure successor start date is valid before proceeding
                              if (!newStartDate) {
                                   console.warn("Invalid newStartDate for successor:",
                                        successor.id, successor, newStartDate, diffInDays);
                                   return;
                              }

                              successor.start_date = newStartDate;
                              successor.end_date = gantt.calculateEndDate(successor.start_date,
                                   successor.duration);

                              updatedTasks.add(successor.id); // Mark the successor for update
                         });
                    }

                    // Uncomment and use this block to apply changes to tasks if necessary
                    if (updatedTasks.size > 0) {
                        gantt.batchUpdate(() => {
                            updatedTasks.forEach(taskId => gantt.updateTask(taskId));
                        });
                    }

                    return true;
               });



               gantt.attachEvent("onAfterTaskAdd", function(id, task) {
                    if (task.type == "project") {
                         task.color = "#00A65A";
                    }
                    updateParent(task);
                    return true; // Allows the task to be added
               });

               document.addEventListener("refreshCriticalData", () => {
                    gantt.clearAll();
                    gantt.load(apiUrl);
               })


               // // Attach event to refresh data after a task is updated
               gantt.attachEvent("onAfterTaskUpdate", function(id, task) {
                    if(task.type == "project"){
                         task.color = "#00A65A";
                    } else {
                         task.color = "";
                    }
                    
                    updateParent(task);
                    task.initial_start_date = task.start_date;
                    return true; // Allows the task to be updated
               });


               // // Attach event to refresh data after a task is deleted
               gantt.attachEvent("onAfterTaskDelete", function(id, task) {
                    updateParent(task);
                    refreshTaskData();
                    return true; // Allows the task to be deleted
               });

               gantt.attachEvent("onAfterLinkAdd", function(id,link){
                    refreshTaskData();
               });

               gantt.attachEvent("onAfterLinkUpdate", function(id,link){
                    refreshTaskData();
               });

               gantt.attachEvent("onAfterLinkDelete", function(id,link){
                    refreshTaskData();
               });

               $('#refreshBtn').on('click', function() { 
                    gantt.render();
               })

               document.getElementById("scaleSelect").addEventListener("change", function (e) {
                    let value = e.target.value;
                    setScaleConfig(value);
                    gantt.render();
               });

               function updateParent(task) {
                    if (task.parent && task.parent !== '0' && task.parent !== null) {
                         let parent = gantt.getTask(task.parent);

                         if (parent.type === "project") {
                              // Get all child tasks of the section
                              let children = gantt.getChildren(task.parent);

                              let latestEndDate = null;
                              let latestStartDate = null;

                              children.forEach(function(childId) {
                                   let child = gantt.getTask(childId);

                                   if (!latestEndDate || child.end_date > latestEndDate) {
                                        latestEndDate = new Date(child
                                             .end_date); // Update latest end_date
                                   }

                                   if (!latestStartDate || child.start_date < latestStartDate) {
                                        latestStartDate = new Date(child
                                             .start_date); // Update latest start_date
                                   }

                              });

                              if (latestEndDate) {
                                   parent.end_date = latestEndDate;
                              }

                              if (latestStartDate) {
                                   parent.start_date = latestStartDate;
                              }

                              gantt.updateTask(parent.id); // Refresh the parent task
                         }
                    }
               }
          })
     </script>
</div>
