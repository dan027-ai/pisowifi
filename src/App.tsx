
import React, { useState } from 'react';
import './App.css';
import ContextDiagram from './components/ContextDiagram';
import ClassDiagram from './components/ClassDiagram';
import MermaidClassDiagram from './components/MermaidClassDiagram';

function App() {
  const [activeDiagram, setActiveDiagram] = useState<'context' | 'class' | 'mermaid'>('context');

  return (
    <div className="App">
      <div className="p-4 flex justify-center space-x-4 mb-4">
        <button
          onClick={() => setActiveDiagram('context')}
          className={`px-4 py-2 rounded ${
            activeDiagram === 'context' 
              ? 'bg-blue-600 text-white' 
              : 'bg-gray-200 hover:bg-gray-300'
          }`}
        >
          Context Diagram
        </button>
        <button
          onClick={() => setActiveDiagram('class')}
          className={`px-4 py-2 rounded ${
            activeDiagram === 'class' 
              ? 'bg-blue-600 text-white' 
              : 'bg-gray-200 hover:bg-gray-300'
          }`}
        >
          Class Diagram
        </button>
        <button
          onClick={() => setActiveDiagram('mermaid')}
          className={`px-4 py-2 rounded ${
            activeDiagram === 'mermaid' 
              ? 'bg-blue-600 text-white' 
              : 'bg-gray-200 hover:bg-gray-300'
          }`}
        >
          Mermaid Diagram
        </button>
      </div>
      
      {activeDiagram === 'context' ? <ContextDiagram /> : 
       activeDiagram === 'class' ? <ClassDiagram /> : <MermaidClassDiagram />}
    </div>
  );
}

export default App;
