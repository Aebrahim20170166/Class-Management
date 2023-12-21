

    var list = ['a','b','c','d','e','f'];


    $.ajaxSetup({
    headers:{
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
}
});
    function updateChoice(choiceID,newValue){
    // document.getElementById(choiceID).innerHTML = newValue;
    // document.getElementById(choiceID).value = newValue;
}

    function removeQuestion(question,questionID) {
    questionCount = document.getElementsByTagName('question')
    if (questionCount <= 1){
    return alert('the quiz must have at least one answers')
}
    if(question['optionCount'].value <= 2){
    return alert('each question must have at least two answers')
}
    $.ajax({
    url: "{{ route('removeQuestion') }}",
    type: 'POST',
    data:{
    id: questionID
},
    success:function(data){
    console.log(data);
    question.remove();
},
    error:function(xhr,status,error){
    $.each(xhr.responseJSON.errors,function (key,item)
{
    alert(item)
}
    );
    // alert(data.code);
}
});
}

    var saveQuestion = function (form){
    var count=form["choices"].length
    var choices=[]
    for(let i=0;i<count;i++){
    choices[i] =form["choices"][i].value;
}
    $.ajax({
    url: "{{ route('updateQuestion') }}",
    type: 'POST',
    datatype:"json",
    data:{
    id: form["questionID"].value,
    content:form["content"].value,
    correctAnswerIndicator:form["correct_answer"].value,
    grade:form["grade"].value,
    choices:choices,
    quizID:{{$quizID}}
},

    success:function(data){
    // alert(data);
    console.log(data);
},
    // error:function(xhr,status,error){
    //     $.each(xhr.responseJSON.errors,function (key,item)
    //         {
    //             alert(item)
    //         }
    //     );
    // }
});
}

    var newOption2 = function(questionID, correctAnswerList,optionCountID) {
    console.log(questionID + " " + " " + correctAnswerList + " " + optionCountID)
    console.log("hellooo")
    var optionDiv = document.createElement('div');

    // create option input
    var optionCountValue = parseInt(optionCountID.value);
    if(optionCountValue == 6)
    return alert("each question must have at more six answers")
    var indicator = optionCountValue;
    optionCountValue +=1;
    optionCountID.value = optionCountValue;

    console.log(" option count " + optionCountValue + " indicator " + indicator);
    console.log(correctAnswerList);

    var newOptionID = questionID + 'option' + optionCountValue;
    var questionOption = document.createElement('input');
    questionOption.type = 'text';
    questionOption.required = 'required'
    questionOption.name = newOptionID;
    questionOption.placeholder = 'option content';
    questionOption.id = newOptionID;
    questionOption.classList.add("Answers");

    // questionOption.size = '40';

    // questionOption.onchange = function(){
    //     console.log( document.getElementById(questionOption.id))
    //     document.getElementById('C'+newOptionID).innerHTML = questionOption.value;
    //     document.getElementById('C'+newOptionID).value = questionOption.value;
    // }


    // create the option and insert it to correct answer list
    var list=['a','b','c','d','e','f'];
    var option = document.createElement('option');
    option.value = list[indicator];
    // option.id = 'C'+newOptionID;
    // option.name = 'C'+newOptionID;

    option.innerHTML = list[indicator];

    correctAnswerList.appendChild(option);
    console.log(correctAnswerList);



    // close button
    var close = document.createElement('input');
    close.type = 'button';
    close.value = 'x';
    close.style.width = '26px';
    close.id = 'Close';
    console.log("hi" + indicator);
    var choiceIndicator = list[indicator];

    close.onclick = function(indicator) {
    var optionCountValue = parseInt(optionCountID.value);
    if(optionCountValue == 2)
    return alert("each question must have at least two answers")
    console.log("indicator " + indicator)

    var correctAnswerIndicator = correctAnswerList.value;
    // var i = optionCountValue;
    // var choiceIndicator = list[--i];
    console.log("cor ans " + correctAnswerIndicator + " choi indi " + choiceIndicator + " indicator " + indicator);

    if(correctAnswerIndicator == choiceIndicator)
    return alert("you can not remove the correct answer")

    if(correctAnswerIndicator > choiceIndicator){
    var index = list.indexOf(correctAnswerIndicator);
    index-=1
    correctAnswerList.selectedIndex = index;

    console.log( correctAnswerIndicator + " with index " + list.indexOf(correctAnswerIndicator));
    console.log("new selection is " + list[index] + " with index " + index)
}

    optionCountValue -= 1;
    optionCountID.value  = optionCountValue;
    listLength = correctAnswerList.length;
    var greatestValue = list[--listLength];
    for(var i=0; i<correctAnswerList.length;i++){
    console.log(i + " " + correctAnswerList[i].value + " " + greatestValue)
    if(correctAnswerList[i].value == greatestValue) {
    correctAnswerList[i].remove();
}
}
    var parent = this.parentNode;
    parent.parentNode.removeChild(parent);
};

    var li = document.createElement('li');
    optionDiv.appendChild(li);
    optionDiv.appendChild(close);
    optionDiv.appendChild(questionOption)
    var location = document.getElementById(questionID+"ol");
    location.appendChild(optionDiv);

    var br2 = document.createElement('br');
    // where.appendChild(br2);


};

    var newQuestion = function() {

    questionsCount = document.getElementById('questionsCount')
    var section = document.createElement('div');

    // 1- add close button
    var close = document.createElement('input');
    close.type = 'button';
    close.value = 'x';
    close.style.width = '26px';
    close.id = 'Close';
    close.onclick = function() {
    var parent = this.parentNode;
    parent.parentNode.removeChild(parent);
};
    section.appendChild(close);



    // create question id and increment value of questions count
    var questionID = parseInt(questionsCount.value);
    questionID +=1;
    questionsCount.value = questionID;

    var optionCount = document.createElement('input');
    optionCount.type = 'hidden';
    optionCount.id = 'optionCount' + questionID;
    optionCount.name = 'optionCount' + questionID;
    optionCount.value = 0;


    var question = document.createElement('input');
    question.type = 'text';
    question.required = 'required';
    // generate name & id
    question.name = 'question'+questionID;
    question.id = 'question'+questionID;
    question.placeholder = 'question body';
    section.appendChild(question);
    var br = document.createElement('br');
    section.appendChild(br);


    var options = document.createElement('ol');
    options.type = 'a';
    options.id = "question"+questionID+"ol";

    section.appendChild(optionCount);

    var correctAnswer = document.createElement('select');
    correctAnswer.name = 'correctAnswer'+questionID;
    section.appendChild(correctAnswer);

    var grade = document.createElement('p');
    grade.innerHTML = 'Question Grade';
    grade.id = 'grade';
    section.appendChild(grade);



    var questionGrade = document.createElement('input');
    questionGrade.type = 'number';
    questionGrade.required = 'required';
    questionGrade.name = 'questionGrade'+ questionID;
    questionGrade.id = 'questionGrade' ;
    questionGrade.style.width = '60px';

    section.appendChild(questionGrade);
    var br1 = document.createElement('br');
    section.appendChild(br1);


    var correct_Answer = document.createElement('p');
    correct_Answer.innerHTML = 'Select Correct Answer';
    correct_Answer.id = 'correct_Answer';
    section.appendChild(correct_Answer);
    section.appendChild(options);



    var addNewOption = document.createElement('a');
    addNewOption.innerHTML = 'Add Answer';
    addNewOption.href = '#';
    addNewOption.id = 'add-new-option';
    addNewOption.onclick = function(){
    newOption2(question.id,correctAnswer,optionCount)
    // console.log(question.id);

}
    section.appendChild(addNewOption);


    var br4 = document.createElement('br');
    section.appendChild(br4);

    document.getElementById('sections').appendChild(section);
    newOption2(question.id,correctAnswer,optionCount)
    newOption2(question.id,correctAnswer,optionCount)

};

    var displayOption = function (question){

    var optionDiv = document.createElement('div');
    var questionID = question['questionID'].value;
    var questionOption = document.createElement('input');
    questionOption.type = 'text';
    var newOptionID =   parseInt(question['optionCount'].value);
    document.getElementById(questionID+'options').value = ++newOptionID;

    questionOption.name = 'choices';
    questionOption.value = '';
    questionOption.placeholder = 'option content';
    questionOption.id = newOptionID;
    questionOption.onchange = function(){
    console.log( document.getElementById(questionOption.id))
    // document.getElementById('C'+newOptionID).innerHTML = questionOption.value;
    // document.getElementById('C'+newOptionID).value = questionOption.value;
}
    var location = document.getElementById(question['questionID'].value)
    // alert("location " + location)
    var option = document.createElement('option');
    var answersCount = question['choices'].length;
    var list=['a','b','c','d','e','f'];
    option.value = list[answersCount];
    option.id = 'C'+newOptionID;
    option.name = 'C'+newOptionID;
    option.innerHTML = list[answersCount];
    question['correct_answer'].appendChild(option);
    var close = document.createElement('input');
    close.type = 'button';
    close.value = 'x';
    close.id='close';
    close.style.width = '26px';
    close.onclick = function() {
    var parent = this.parentNode;
    parent.parentNode.removeChild(parent);
    document.getElementById(option.id).remove();
};
    var li = document.createElement('li');
    optionDiv.appendChild(li);
    optionDiv.appendChild(close);
    optionDiv.appendChild(questionOption)
    var optionsLocation = document.getElementById(questionID+"ol");
    optionsLocation.appendChild(optionDiv);
    console.log(optionsLocation);
    var br2 = document.createElement('br');
    location.appendChild(br2);
}

    var newOption = function(question) {

    $.ajax({
        url: "{{ route('addOption') }}",
        type: 'POST',
        datatype:"json",
        data:{
            id: question["questionID"].value,
            quizID:{{$quizID}},
    answersCount:question["choices"].length

},
    success:function(optionID){
    console.log(optionID);
    displayOption(question,optionID);
},
});
};

    var removeChoice = function (choiceNode,choiceID,questionID,choicIndicator){
    var list = ['a','b','c','d','e','f'];
    var question = document.getElementById(questionID);
    var correctAnswerIndicator = question["correct_answer"].value
    var isCorrectAnswerChange = "false";
    if(correctAnswerIndicator == choicIndicator){
    return alert("you can not remove the correct answer")
}
    if(questionID['optionCount'].value <= 2){
    return alert("each question must have at least two answers")
}
    if(correctAnswerIndicator > choicIndicator)
    isCorrectAnswerChange = "true"

    $.ajax({
    url: "{{ route('removeChoice') }}",
    type: 'POST',
    data:{
    choiceID: choiceID,
    questionID:questionID,
    isCorrectAnswerChange: isCorrectAnswerChange
},
    success:function(newCorrectAnswerIndicator){
    console.log(newCorrectAnswerIndicator);
    choiceNode.remove();
    var answersCount = question["correct_answer"].length;
    var greatestValue = list[--answersCount];
    console.log(greatestValue);

    if(isCorrectAnswerChange == "true") {
    for (let i = 0; i < answersCount; i++) {
    if (question["correct_answer"][i].value == newCorrectAnswerIndicator)
    question["correct_answer"].selectedIndex = i;
}
}

    for(let i = 0; i<=answersCount;i++){
    console.log(question["correct_answer"][i].value + " " + greatestValue);
    if(question["correct_answer"][i].value == greatestValue) {

    question["correct_answer"][i].remove();
}
}

    // if(isCorrectAnswerChange == "true"){
    //     question["correct_answer"].selectedIndex = newCorrectAnswerIndicator;
    // }
    // console.log("hi")
    // for(let i =0; i<answersCount;i++){
    //
    //     console.log(question["correct_answer"][i].value)
    //     if(question["correct_answer"][i].value == greatestValue) {
    //         console.log("innnnn")
    //         question["correct_answer"][i].remove();
    //     }
    // }
    // if(isCorrectAnswerChange == "true")
    //     question["correct_answer"].selectedIndex = newCorrectAnswerIndicator;

},
    error:function(xhr,status,error){
    $.each(xhr.responseJSON.errors,function (key,item)
{
    alert(item)
}
    );
    // alert(data.code);
}
});
}

