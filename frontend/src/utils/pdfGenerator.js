import jsPDF from 'jspdf';
import { calculateAge } from './ageCalculator';

export const generatePatientPDF = (patient) => {
  const doc = new jsPDF();
  
  doc.setFont('helvetica');
  
  doc.setFillColor(59, 130, 246); 
  doc.rect(0, 0, 210, 35, 'F');
  
  doc.setTextColor(255, 255, 255); 
  doc.setFontSize(22);
  doc.text('FICHA DEL PACIENTE', 105, 15, { align: 'center' });
  
  doc.setTextColor(0, 0, 0);
  
  let yPosition = 50;
  
  doc.setFontSize(16);
  doc.setFont('helvetica', 'bold');
  doc.text('Información Personal', 20, yPosition);
  
  yPosition += 10;
  doc.setFontSize(11);
  doc.setFont('helvetica', 'normal');
  
  const personalInfo = [
    { label: 'Nombre completo:', value: `${patient.name} ${patient.paternal_surname} ${patient.maternal_surname || ''}` },
    { label: 'Fecha de nacimiento:', value: formatDate(patient.birth_date) },
    { label: 'Edad:', value: `${calculateAge(patient.birth_date)} años` },
    { label: 'Sexo:', value: patient.sex === 'M' ? 'Masculino' : 'Femenino' },
    { label: 'Lugar de origen:', value: patient.origin_city },
    { label: 'Fecha de ingreso:', value: formatDate(patient.admission_date) },
  ];
  
  personalInfo.forEach(item => {
    doc.setFont('helvetica', 'bold');
    doc.text(item.label, 20, yPosition);
    doc.setFont('helvetica', 'normal');
    doc.text(item.value, 75, yPosition);
    yPosition += 8;
  });
    
  yPosition += 5;
  doc.setFontSize(16);
  doc.setFont('helvetica', 'bold');
  doc.text('Tutor Responsable', 20, yPosition);
  
  yPosition += 10;
  doc.setFontSize(11);
  doc.setFont('helvetica', 'normal');
  
  const tutorInfo = [
    { label: 'Nombre del tutor:', value: patient.tutor?.name || 'N/A' },
    { label: 'Teléfono:', value: patient.tutor?.phone || 'N/A' },
  ];
  
  tutorInfo.forEach(item => {
    doc.setFont('helvetica', 'bold');
    doc.text(item.label, 20, yPosition);
    doc.setFont('helvetica', 'normal');
    doc.text(item.value, 75, yPosition);
    yPosition += 8;
  });


  //información del hospital de procedencia
    yPosition += 5;
    doc.setFontSize(16);
    doc.setFont('helvetica', 'bold');
    doc.text('Hospital de Procedencia', 20, yPosition);
    yPosition += 10;
    doc.setFontSize(11);
    doc.setFont('helvetica', 'normal');
    doc.setFont('helvetica', 'bold');
    doc.text('Nombre del hospital:', 20, yPosition);
    doc.setFont('helvetica', 'normal');
    doc.text(patient.hospital?.name || 'N/A', 75, yPosition);
    yPosition += 8;
    doc.setFont('helvetica', 'bold');
    doc.text('Ciudad:', 20, yPosition);
    doc.setFont('helvetica', 'normal');
    doc.text(patient.hospital?.city || 'N/A', 75, yPosition);

  doc.setFontSize(9);
  doc.setTextColor(128, 128, 128);
  doc.text(`Generado el ${new Date().toLocaleString('es-MX')}`, 105, 280, { align: 'center' });
  doc.text(`ID del paciente: ${patient.id}`, 105, 285, { align: 'center' });
  
  const fileName = `Paciente_${patient.name}_${patient.paternal_surname}_${new Date().getTime()}.pdf`;
  
  doc.save(fileName);
};


const formatDate = (dateString) => {
  const date = new Date(dateString + 'T00:00:00');
  return date.toLocaleDateString('es-MX', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  });
};
