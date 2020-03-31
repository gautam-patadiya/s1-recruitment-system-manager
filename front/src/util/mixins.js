import moment from "moment";
import {ExportToCsv} from "export-to-csv";
import * as jsPDF from 'jspdf';
import 'jspdf-autotable';

export default {
    data() {
        return {
            csv: {
                config: {
                    showLabels: true,
                    useKeysAsHeaders: false,
                    filename: 'export',
                    headers: [],
                },
                dataSource: []
            },
            pdf: {
                title: 'Exported Data',
                showHead: 'everyPage',
                pageBreak: 'auto',
                filename: 'data',
                body: [],
                columns: []
            }
        }
    },
    methods: {
        momentFormatter(date) {
            return (date) ? moment(date) : moment();
        },
        exportCsv() {
            const csvExporter = new ExportToCsv(this.csv.config);
            csvExporter.generateCsv(this.csv.dataSource);
        },
        exportPdf() {
            const pdf = new jsPDF('l', 'pt');
            pdf.text(this.pdf.title, 30, 30);
            pdf.autoTable({...this.pdf,
                startY: 50,
                margin: {
                    top: 30,
                    right: 30,
                    bottom: 30,
                    left: 30
                }
            });
            pdf.save(`${this.pdf.filename}.pdf`);
        }
    }
}
