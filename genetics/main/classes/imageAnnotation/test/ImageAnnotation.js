/*  ImageAnnotation - The JavaScript image annotation backing bean API
 *  (c) 2007 Guillaume Texier
 *
 *  ImageAnnotation is a class which talks with the backing bean.
/*--------------------------------------------------------------------------*/

// =======================================

function ImageAnnotation(containerId,notesArray)
{
//this.ref, this.schemaName, this.fieldName

	//DEBUG('ImageAnnotation() {');

	// Constants
	this.NOTE_DEFAULT_TEXT		= '';
	this.NOTE_DEFAULT_LEFT		= 10;
	this.NOTE_DEFAULT_TOP		= 10;
	this.NOTE_DEFAULT_WIDTH		= 50;
	this.NOTE_DEFAULT_HEIGHT	= 50;

	// Properties
	this.notes			= null;

	// Initialization

	// Creates the Photo Note Container
	this.notes = new PhotoNoteContainer(document.getElementById(containerId));

	// Action
	//DEBUG('typeof(notesArray)='+typeof(notesArray))
	//DEBUG('instanceof Array='+(notesArray instanceof Array))

	// Creates and add notes objects to notes container if a notesArray is passed
	if (notesArray instanceof Array) {
		for (i=0;i<notesArray.length;i++) {
			//DEBUG('note:'+i);
			//DEBUG(notesArray[i].toString());
	                // create a note object, and add them to the notes container
	                var newNote		= new PhotoNote(notesArray[i]);
/*
	                with (notesArray[i]) {
	                	//var newNote		= new PhotoNote(text,id,new PhotoNoteRect(left,top,width,height));
	                }
*/
	                // Add events handlers
	                newNote.onsave		= this.save;
	                newNote.ondelete	= this.remove;
	                // Add note to notes container
	                if (!this.notes.AddNote(newNote)) break;
		}
	}

	//DEBUG('ImageAnnotation }');
};


// =======================================

ImageAnnotation.prototype.addNote = function()
{
	//DEBUG('addNote() {');

	// Add note if container is not in readonly mode
	if (!this.notes.isReadonly()) {
		// Creates the note object
		var newNote		= new PhotoNote(this.NOTE_DEFAULT_TEXT,-1,new PhotoNoteRect(this.NOTE_DEFAULT_LEFT,this.NOTE_DEFAULT_TOP,this.NOTE_DEFAULT_WIDTH,this.NOTE_DEFAULT_HEIGHT));
                // Add events handlers
                newNote.onsave		= this.save;
                newNote.ondelete	= this.remove;
		if (this.notes.AddNote(newNote)) {
			newNote.Select();
		} else {
			newNote	= null;
		}
	}

	//DEBUG('addNote }');
}

// =======================================

ImageAnnotation.prototype.setReadonly = function(readonly)
{
	//DEBUG('setReadonly() {');

	this.notes.setReadonly(readonly);

	//DEBUG('setReadonly }');
}

// =======================================

ImageAnnotation.prototype.toggleReadonly = function()
{
	//DEBUG('toggleReadonly() {');

	this.notes.toggleReadonly();

	//DEBUG('toggleReadonly }');
}

// =======================================

ImageAnnotation.prototype.save = function(note)
{
	//DEBUG('SAVE');
	//DEBUG(note.toXML());
	//Seam.Component.getInstance("ImageAnnotationActions").save(this.ref, this.schemaName, this.fieldName, note.id,
	//  note.rect.left, note.rect.top, note.rect.width, note.rect.height, note.text);
//	//DEBUG("SAVE : ['+id+'] ('+x0+','+y0+','+width+','+height+')')
//	//DEBUG("TEXT : '+text)
	return 1;
}

// =======================================

ImageAnnotation.prototype.remove = function(note)
{
	//DEBUG('REMOVE');
	//DEBUG(note.toString());
//	Seam.Component.getInstance("ImageAnnotationActions").remove(this.ref, this.schemaName, this.fieldName, note.id);

//	//DEBUG("REMOVE : ['+id+']')
	return true;

}

// =======================================

ImageAnnotation.prototype.showAllNotes = function()
{
	//DEBUG('showAllNotes');
	this.notes.ShowAllNotes();
}

// =======================================

ImageAnnotation.prototype.hideAllNotes = function()
{
	//DEBUG('hideAllNotes');
	this.notes.HideAllNotes();
}

// =======================================

ImageAnnotation.prototype.isReadonly = function()
{
	//DEBUG('isReadOnly');
	return this.notes.isReadonly();
}

// =======================================
