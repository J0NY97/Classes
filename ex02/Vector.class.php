<?php
	require_once "Vertex.class.php";

	class Vector
	{
		private $_x;
		private $_y;
		private $_z;
		private $_w = 0;
		static $verbose = False;

		function __construct(array $args)
		{
			if (isset($args["orig"]))
			{
				$orig = new Vertex(array('x' => $args["orig"]->getX(),
										'y' => $args["orig"]->getY(),
										'z' => $args["orig"]->getZ()));
			}
			else
				$orig = new Vertex(array('x' => 0, 'y' => 0, 'z' => 0, 'w' => 1));
			if (isset($args["dest"]))
			{
				$this->_x = $args["dest"]->getX() - $orig->getX();
				$this->_y = $args["dest"]->getY() - $orig->getY();
				$this->_z = $args["dest"]->getZ() - $orig->getZ();
			}
			if (self::$verbose)
				print ($this." constructed\n");
		}

		function __destruct()
		{
			if (self::$verbose)
				print ($this." destructed\n");
		}
		
		function __toString()
		{
			return (sprintf("Vector( x:%.2f, y:%.2f z:%.2f, w:%.2f )",
							$this->_x, $this->_y, $this->_z, $this->_w));	
		}

		static function doc()
		{
			$file_name = "Vector.doc.txt";
			if (file_exists($file_name))
				return (file_get_contents($file_name));
			else
				return ("Doc file doesn't exist\n");
		}

		function magnitude()
		{
			return (sqrt(($this->_x * $this->_x) +
						($this->_y * $this->_y) +
						($this->_z * $this->_z)));
		}

		function normalize()
		{
			$len = $this->magnitude();
			return (new Vector(array("dest" =>
						new Vertex(array('x' => $this->_x / $len,
										'y' => $this->_y / $len,
										'z' => $this->_z / $len)))));
		}

		function add(Vector $rhs)
		{
			return (new Vector(array("dest" =>
				new Vertex(array('x' => $this->_x + $rhs->_x,
								'y' => $this->_y + $rhs->_y,
								'z' => $this->_z + $rhs->_z)))));
		}

		function sub(Vector $rhs)
		{
				return (new Vector(array("dest" =>
				new Vertex(array('x' => $this->_x - $rhs->_x,
								'y' => $this->_y - $rhs->_y,
								'z' => $this->_z - $rhs->_z)))));
		}

		function opposite()
		{
			return (new Vector(array("dest" =>
				new Vertex(array('x' => -$this->_x,
								'y' => -$this->_y,
								'z' => -$this->_z)))));
		}

		function scalarProduct($k)
		{
			return (new Vector(array("dest" =>
						new Vertex(array('x' => $this->_x * $k,
										'y' => $this->_y * $k,
										'z' => $this->_z * $k)))));
		}

		function dotProduct(Vector $rhs)
		{
			return (($this->_x * $rhs->_x) +
					($this->_y * $rhs->_y) +
					($this->_z * $rhs->_z));
		}

		function cos(Vector $rhs)
		{
			return ($this->dotProduct($rhs) / ($this->magnitude() * $rhs->magnitude()));
		}

		function crossProduct(Vector $rhs)
		{
			return (new Vector(array("dest" =>
						new Vertex(array(
							'x' => $this->_y * $rhs->_z - $this->_z * $rhs->_y,
							'y' => $this->_z * $rhs->_x - $this->_x * $rhs->_z,
							'z' => $this->_x * $rhs->_y - $this->_y * $rhs->_x)))));
		}

		function getX()
		{
			return $this->_x;
		}
		
		function getY()
		{
			return $this->_y;
		}
		
		function getZ()
		{
			return $this->_z;
		}
	}
?>
